<?php

namespace Oro\Bundle\InstallerBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Oro\Bundle\SecurityBundle\Command\LoadPermissionConfigurationCommand;

class PlatformUpdateCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('oro:platform:update')
            ->setDescription('Execute platform application update commands and init platform assets.')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Forces operation to be executed.'
            )
            ->addOption(
                'skip-assets',
                null,
                InputOption::VALUE_NONE,
                'Skip UI related commands during update'
            )
            ->addOption('symlink', null, InputOption::VALUE_NONE, 'Symlinks the assets instead of copying it')
            ->addOption(
                'skip-translations',
                null,
                InputOption::VALUE_NONE,
                'Determines whether translation data need to be loaded or not'
            );

        parent::configure();
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = $input->getOption('force');

        if ($force) {
            $assetsOptions = [
                '--exclude' => ['OroInstallerBundle']
            ];
            if ($input->hasOption('symlink') && $input->getOption('symlink')) {
                $assetsOptions['--symlink'] = true;
            }

            $commandExecutor = $this->getCommandExecutor($input, $output);

            $commandExecutor
                ->runCommand(
                    'oro:migration:load',
                    [
                        '--process-isolation' => true,
                        '--force'             => true,
                        '--timeout'           => $commandExecutor->getDefaultOption('process-timeout')
                    ]
                )
                ->runCommand(LoadPermissionConfigurationCommand::NAME, ['--process-isolation' => true])
                ->runCommand(
                    'oro:workflow:definitions:load',
                    ['--process-isolation' => true]
                )
                ->runCommand(
                    'oro:cron:definitions:load',
                    [
                        '--process-isolation' => true
                    ]
                )
                ->runCommand('oro:process:configuration:load', ['--process-isolation' => true])
                ->runCommand('oro:migration:data:load', ['--process-isolation' => true])
                ->runCommand('oro:navigation:init', ['--process-isolation' => true])
                ->runCommand('router:cache:clear', ['--process-isolation' => true])
                ->runCommand('oro:message-queue:create-queues', ['--process-isolation' => true])
            ;

            if (!$input->getOption('skip-translations')) {
                $commandExecutor
                    ->runCommand('oro:translation:load', ['--process-isolation' => true]);
            }

            if (!$input->getOption('skip-assets')) {
                $commandExecutor
                    ->runCommand('oro:assets:install', $assetsOptions)
                    ->runCommand('assetic:dump')
                    ->runCommand('fos:js-routing:dump', ['--process-isolation' => true])
                    ->runCommand('oro:localization:dump', ['--process-isolation' => true])
                    ->runCommand('oro:translation:dump', ['--process-isolation' => true])
                    ->runCommand(
                        'oro:requirejs:build',
                        ['--ignore-errors' => true, '--process-isolation' => true]
                    );
            }
        } else {
            $output->writeln(
                '<comment>ATTENTION</comment>: Database backup is highly recommended before executing this command.'
            );
            $output->writeln('           Please, remove application cache before run this command.');
            $output->writeln('');
            $output->writeln('To force execution run command with <info>--force</info> option:');
            $output->writeln(sprintf('    <info>%s --force</info>', $this->getName()));
        }
    }
}
