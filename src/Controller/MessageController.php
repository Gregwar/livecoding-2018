<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Channel;
use App\Entity\LastView;

/**
 * @Route("/chat")
 */
class MessageController extends AbstractController
{
    protected function updateLastView(Channel $channel)
    {
        $em = $this->getDoctrine()->getManager();
        $lastViewRepository = $em->getRepository(LastView::class);
        if (count($channel->getMessages()) > 0) {
            $lastView = $lastViewRepository->findOneBy([
                'channel' => $channel,
                'user' => $this->getUser()
            ]);

            if (!$lastView) {
                $lastView = new LastView;
                $lastView
                    ->setChannel($channel)
                    ->setUser($this->getUser())
                    ;
                $em->persist($lastView);
            }

            $lastView->setMessage($channel->getMessages()[0]);
            $em->flush();
        }
    }

    /**
     * @Route("/channel/{id}", name="message_index")
     */
    public function index(
            Channel $channel,
            MessageRepository $messageRepository, 
            Request $request
            ): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $message->setUser($this->getUser());
            $message->setChannel($channel);
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('message_index', [
                'id' => $channel->getId()
            ]);
        }
        $this->updateLastView($channel);

        return $this->render('message/index.html.twig', [
            'channel' => $channel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/messages/{id}", name="chat_messages")
     */
    public function messages(Channel $channel, MessageRepository $messageRepository)
    {
        $this->updateLastView($channel);

        return $this->render('message/_messages.html.twig', [
            'channel' => $channel
        ]);
    }

    /**
     * @Route("/delete/{id}", name="message_delete")
     */
    public function delete(Message $message)
    {
        $channel = $message->getChannel();

        if ($this->getUser() == $message->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();
        }

        return $this->redirectToRoute('message_index', [
            'id' => $channel->getId()
        ]);
    }
}
