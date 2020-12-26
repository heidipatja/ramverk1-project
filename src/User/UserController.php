<?php

namespace Hepa19\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Hepa19\User\HTMLForm\UserLoginForm;
use Hepa19\User\HTMLForm\CreateUserForm;
use Hepa19\User\HTMLForm\UpdateUserForm;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var $data description
     */
    //private $data;



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     ;
    // }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");

        $page->add("anax/v2/article/default", [
            "content" => "An index page",
        ]);

        return $page->render([
            "title" => "A index page",
        ]);
    }



    /**
     * Login page
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $form = new UserLoginForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        $page->add("user/login");

        return $page->render([
            "title" => "Logga in",
        ]);
    }



    /**
     * Logout route
     * Clear user information from session and redirect
     *
     * @return object as a response object
     */
    public function logoutAction() : object
    {
        $session = $this->di->get("session");
        $session->delete("username");
        $session->delete("userId");

        return $this->di->get("response")->redirect("user/login")->send();
    }



    /**
     * Create new user account
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $form = new CreateUserForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Ny användare",
        ]);
    }



    /**
     * Edit user
     *
     * @param integer $id get details on user with id.
     *
     * @return object as a response object
     */
    public function updateAction($id) : object
    {
        $page = $this->di->get("page");
        $form = new UpdateUserForm($this->di, $id);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Redigera användare",
        ]);
    }



    /**
     * Profile page
     *
     * @return object as a response object
     */
    public function profileAction() : object
    {
        $page = $this->di->get("page");
        $session = $this->di->get("session");

        $user = new User();
        $username = $session->get("username") ?? null;

        if (!$username) {
            return $this->di->get("response")->redirect("user/login")->send();
        }

        $user->setDb($this->di->get("dbqb"));
        $user = $user->find("username", $username);
        $gravatar = $user->getGravatar($user->email);

        $page->add("user/profile", [
            "user" => $user,
            "gravatar" => $gravatar
        ]);

        return $page->render([
            "title" => "Profil",
        ]);
    }
}
