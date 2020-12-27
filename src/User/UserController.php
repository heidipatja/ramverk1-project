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
     * Index page showing all users
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $page->add("user/crud/view-all", [
            "users" => $user->findAll(),
        ]);

        return $page->render([
            "title" => "Användare",
        ]);
    }



    /**
     * Login page
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
