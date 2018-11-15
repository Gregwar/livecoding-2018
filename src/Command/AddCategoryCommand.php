<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

class AddCategoryCommand extends Command
{
    protected static $defaultName = 'app:add-category';
    protected $manager;

    public function __construct(ObjectManager $manager)
    {
        parent::__construct();
        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('category-name', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('category-name');

        $category = new Category;
        $category->setName($name);
        $this->manager->persist($category);
        $this->manager->flush();

        $io->success('Category added!');
    }
}
