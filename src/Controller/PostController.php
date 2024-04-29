<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
    //List The Posts
    #[Route('/postlist',name:'app_list_posts')]
    public function getAll(PostRepository $repo){
        $posts = $repo->findAll();
        return $this->render('post/displayPosts.html.twig',[
            'posts'=>$posts
        ]);
    }
    //List The Posts by Their Respective Forums
    #[Route('/postlist_{idf}',name:'app_list_posts_by_forum')]
    public function getpostsbyidforum($idf,PostRepository $repo){
        $posts = $repo->getPostsByForumNormalSQL($idf);
        return $this->render('post/displayPosts.html.twig',[
            'posts'=>$posts
        ]);
    }
    //Add a Post
    #[Route('/postadd',name:'app_add_post')]
    public function AddPost(Request $req,ManagerRegistry $manager,UserRepository $repoUser,ForumRepository $repoForum){
        $NewPost = new Post();
        $form = $this->createForm(PostType::class,$NewPost);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            //setting Forum
            $Forum = $repoForum->find(1);
            $NewPost->setIdForum($Forum);
            //setting User
            $User = $repoUser->find(1);
            $NewPost->setIdUser($User);
            //setting the time
            $today = new DateTime();
            $NewPost->setTimeofcreation($today);
            //setting the Likes
            $NewPost->setLikeNumber(0);
            $NewPost->getIdForum()->setRepliesNumber($NewPost->getIdForum()->getRepliesNumber()+1);
            //Saving The Post
            $manager->getManager()->persist($NewPost);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts');
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView()]);
    }
    //Add a Post witth Different Forum
    #[Route('/postadd_{idf}',name:'app_add_post_diff_forum')]
    public function AddPostForum($idf,Request $req,ManagerRegistry $manager,UserRepository $repoUser,ForumRepository $repoForum){
        $NewPost = new Post();
        $form = $this->createForm(PostType::class,$NewPost);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            //setting Forum
            $Forum = $repoForum->find($idf);
            $NewPost->setIdForum($Forum);
            //setting User
            $User = $repoUser->find(4);
            $NewPost->setIdUser($User);
            //setting the time
            $today = new DateTime();
            $NewPost->setTimeofcreation($today);
            //setting the Likes
            $NewPost->setLikeNumber(0);
            $NewPost->getIdForum()->setRepliesNumber($NewPost->getIdForum()->getRepliesNumber()+1);
            //Saving The Post
            $manager->getManager()->persist($NewPost);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts');
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView()]);
    }
    //Add a Post witth Different User and Forum
    #[Route('/postadd{idu}_{idf}',name:'app_add_post_diff_user&forum')]
    public function AddPost2($idu,$idf,Request $req,ManagerRegistry $manager,UserRepository $repoUser,ForumRepository $repoForum){
        $NewPost = new Post();
        $form = $this->createForm(PostType::class,$NewPost);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            //setting Forum
            $Forum = $repoForum->find($idf);
            $NewPost->setIdForum($Forum);
            //setting User
            $User = $repoUser->find($idu);
            $NewPost->setIdUser($User);
            //setting the time
            $today = new DateTime();
            $NewPost->setTimeofcreation($today);
            //setting the Likes
            $NewPost->setLikeNumber(0);
            $NewPost->getIdForum()->setRepliesNumber($NewPost->getIdForum()->getRepliesNumber()+1);
            //Saving The Post
            $manager->getManager()->persist($NewPost);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts');
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView()]);
    }
    //Update Post
    #[Route('/postupdate{id}',name:'app_update_post')]
    public function update(PostRepository $rep,$id,Request $req,ManagerRegistry $manager){
        $post = $rep->find($id);
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($req);
        $forumid = $post->getIdForum()->getIdForum();
        if($form->isSubmitted()){
            $manager->getManager()->persist($post);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts_by_forum', [
                'idf' => $forumid,
            ]);
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView()]);
    }
    //Delete the Forums
    #[Route('/postdelete_{id}',name:'app_delete_post')]
    public function delete($id,ManagerRegistry $manager,PostRepository $repo){
        $post = $repo->find($id);
        $manager->getManager()->remove($post);
        $manager->getManager()->flush();
        $forumid = $post->getIdForum()->getIdForum();
        return $this->redirectToRoute('app_list_posts_by_forum', [
            'idf' => $forumid,
        ]);
    }
}
