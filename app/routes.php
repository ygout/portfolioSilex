<?php

use Symfony\Component\HttpFoundation\Request;
use Portfolio\Domain\Projet;
use Portfolio\Domain\Categorie;
use Portfolio\Domain\User;
use Portfolio\Form\Type\ProjetType;
use Portfolio\Form\Type\CategorieType;
use Portfolio\Form\Type\UserType;

// Home page
$app->get('/', function () use ($app) {

            return $app['twig']->render('index.html.twig');
        });

// Projet page
$app->get('/projets', function() use ($app) {
            $categories = $app['dao.categorie']->findAll();
            $projets = $app['dao.projet']->findAll();
            return $app['twig']->render('projet.html.twig', array('categories' => $categories, 'projets' => $projets));
        });

// Projet details
$app->get('/projets/{id}', function($id) use ($app) {

            $projet = $app['dao.projet']->find($id);
            return $app['twig']->render('detail.html.twig', array('projet' => $projet));
        });

// Login form
$app->get('/login', function(Request $request) use ($app) {
            return $app['twig']->render('login.html.twig', array(
                        'error' => $app['security.last_error']($request),
                        'last_username' => $app['session']->get('_security.last_username'),
            ));
        })->bind('login');  // named route so that path('login') works in Twig templates
// Admin home page
$app->get('/admin', function() use ($app) {
            $categories = $app['dao.categorie']->findAll();
            $projets = $app['dao.projet']->findAll();
            $users = $app['dao.user']->findAll();
            return $app['twig']->render('admin.html.twig', array(
                        'categories' => $categories,
                        'projets' => $projets,
                        'users' => $users));
        });

// Add a new categorie
$app->match('/admin/categorie/add', function(Request $request) use ($app) {
            $categorie = new Categorie();
            $categorieForm = $app['form.factory']->create(new CategorieType(), $categorie);
            $categorieForm->handleRequest($request);
            if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
                $app['dao.categorie']->save($categorie);
                $app['session']->getFlashBag()->add('success', 'The category was successfully created.');
            }
            return $app['twig']->render('categorie_form.html.twig', array(
                        'libelle' => 'New categorie',
                        'categorieForm' => $categorieForm->createView()));
        });

// Edit an existing category
$app->match('/admin/categorie/{id}/edit', function($id, Request $request) use ($app) {
            $categorie = $app['dao.categorie']->find($id);
            $categorieForm = $app['form.factory']->create(new CategorieType(), $categorie);
            $categorieForm->handleRequest($request);
            if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
                $app['dao.categorie']->save($categorie);
                $app['session']->getFlashBag()->add('success', 'The category was succesfully updated.');
            }
            return $app['twig']->render('categorie_form.html.twig', array(
                        'libelle' => 'Edit categorie',
                        'categorieForm' => $categorieForm->createView()));
        });

// Remove a category
$app->get('/admin/categorie/{id}/delete', function($id, Request $request) use ($app) {

            // Delete the category
            $app['dao.categorie']->delete($id);
            $app['session']->getFlashBag()->add('success', 'The category was succesfully removed.');
            return $app->redirect('/admin');
        });
// Add a user
$app->match('/admin/user/add', function(Request $request) use ($app) {
            $user = new User();
            $userForm = $app['form.factory']->create(new UserType(), $user);
            $userForm->handleRequest($request);
            if ($userForm->isSubmitted() && $userForm->isValid()) {
                // generate a random salt value
                $salt = substr(md5(time()), 0, 23);
                $user->setSalt($salt);
                $plainPassword = $user->getPassword();
                // find the default encoder
                $encoder = $app['security.encoder.digest'];
                // compute the encoded password
                $password = $encoder->encodePassword($plainPassword, $user->getSalt());
                $user->setPassword($password);
                $app['dao.user']->save($user);
                $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
            }
            return $app['twig']->render('user_form.html.twig', array(
                        'title' => 'New user',
                        'userForm' => $userForm->createView()));
        });

// Edit an existing user
$app->match('/admin/user/{id}/edit', function($id, Request $request) use ($app) {
            $user = $app['dao.user']->find($id);
            $userForm = $app['form.factory']->create(new UserType(), $user);
            $userForm->handleRequest($request);
            if ($userForm->isSubmitted() && $userForm->isValid()) {
                $plainPassword = $user->getPassword();
                // find the encoder for the user
                $encoder = $app['security.encoder_factory']->getEncoder($user);
                // compute the encoded password
                $password = $encoder->encodePassword($plainPassword, $user->getSalt());
                $user->setPassword($password);
                $app['dao.user']->save($user);
                $app['session']->getFlashBag()->add('success', 'The user was succesfully updated.');
            }
            return $app['twig']->render('user_form.html.twig', array(
                        'title' => 'Edit user',
                        'userForm' => $userForm->createView()));
        });

// Remove a user
$app->get('/admin/user/{id}/delete', function($id, Request $request) use ($app) {

            // Delete the user
            $app['dao.user']->delete($id);
            $app['session']->getFlashBag()->add('success', 'The user was succesfully removed.');
            return $app->redirect('/admin');
        });

$app->get('/admin/projet/add', function() use($app) {
            $categories = $app['dao.categorie']->findAll();
            return $app['twig']->render('projet_form.html.twig', array('categories' => $categories));
        })->bind('projet/add');

$app->post('admin/projet/add', function() use($app) {
            $projet = new Projet();
            $categorie = new Categorie();
            $request = $app['request'];
            //$idCat=$request->get
            $categorie = $app['dao.categorie']->find($request->get('idCategorie'));

            $projet->setTitre($request->get('titre'));
            $projet->setCategorie($categorie);
            $projet->setLangages($request->get('langages'));
            $projet->setOutils($request->get('outils'));
            $projet->setDescription($request->get('description'));
            $projet->setBilan($request->get('bilan'));

            $app['dao.projet']->save($projet);
            $app['session']->getFlashBag()->add('success', 'Projet ajouté.');
            return $app->redirect('/admin');
        });

$app->get('/admin/projet/{id}/delete', function($id, Request $request) use ($app) {

            // Delete the projet
            $app['dao.projet']->delete($id);
            $app['session']->getFlashBag()->add('success', 'Projet supprimé.');
            return $app->redirect('/admin');
        });

$app->get('/admin/projet/{id}/edit', function($id, Request $request) use ($app) {

            // Delete the projet
            $app['dao.projet']->edit($id);
            $app['session']->getFlashBag()->add('success', 'Projet supprimé.');
            return $app->redirect('/admin');
        });


//Contact form
$app->get('/contact', function() use ($app) {
            return $app['twig']->render('contact.html.twig');
        })->bind('contact');

//Send mail contact
$app->post('/contact', function() use ($app) {
            $request = $app['request'];

            $message = \Swift_Message::newInstance()
                    ->setSubject(array($request->get('subject')))
                    ->setFrom(array($request->get('email') => $request->get('name')))
                    ->setTo(array('yohann.goutaret@gmail.com'))
                    ->setBody($request->get('message'));

            $app['mailer']->send($message);

            return $app['twig']->render('contact.html.twig', array('sent' => true));
        });

// cv page
$app->get('/cv', function () use ($app) {

            return $app['twig']->render('cv.html.twig');
        });