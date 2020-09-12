<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Users;
use App\Form\BlogType;
use App\Entity\Contact;
use App\Entity\Articles;
use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Form\EditProfileType;
use App\Repository\UsersRepository;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
        return $this->render('users/index.html.twig');
    }

    /**
     * @Route("/users/profil/edit", name="users_profil_edit")
     */
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(editProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('users');
        }

        return $this->render('users/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users/pass/edit", name="users_pass_edit")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            // on vérifie si les deux mots de passe sont identiques
            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succès');

                return $this->redirectToRoute('users');
            } else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }
        return $this->render('users/editpass.html.twig');
    }

    /**
     * @Route("/manageUsers", name="users_manage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function usersManage(UsersRepository $usersRepo)
    {
        return $this->render('/users/manageUsers.html.twig', [
            'users' => $usersRepo->findAll()
        ]);
    }

    /**
     * @Route("users/delete/{id}", name="users_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function usersDelete(Users $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('message', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('users_manage');
    }

    /**
     * @Route("/manageContact", name="contact_manage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function contactManage(ContactRepository $contactRepo)
    {
        return $this->render('/users/manageContact.html.twig', [
            'contact' => $contactRepo->findAll()
        ]);
    }

    /**
     * @Route("contact/delete/{id}", name="contact_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function contactDelete(Contact $contact)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();

        $this->addFlash('message', 'Message supprimé avec succès');
        return $this->redirectToRoute('contact_manage');
    }
}