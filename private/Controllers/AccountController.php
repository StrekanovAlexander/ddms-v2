<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Account;
use \Respect\Validation\Validator;

class AccountController extends Controller {

    public function index($request, $response) {
        $accounts = Account::get();
        return $this->view->render($response, 'admin/account/index.twig', [
            'accounts' => $accounts,
            'breadcrumbs' => Pages::breadcrumbs([
                ['Реквізити для оплати'],
            ], true)
        ]);
    }

    public function details($request, $response, $args) {
        $account = Account::find($args['id']);
    
        $file = Files::getPath([
          $this->abspath, 'public', 'docs', $account->src
        ]);
    
        return $this->view->render($response, 'admin/account/details.twig', [
          'account' => $account,
          'file' => file_exists($file) && $account->src ? $file : null,
          'breadcrumbs' => Pages::breadcrumbs([
            ['Реквізити для оплати', 'admin.accounts'],
            [$account->purpose],
          ], true)
        ]);      
      }
    
      public function getUpdate($request, $response, $args) {
        $account = Account::find($args['id']);
        return $this->view->render($response, 'admin/account/update.twig', [
          'account' => $account,
          'breadcrumbs' => Pages::breadcrumbs([
            ['Реквізити для оплати', 'admin.accounts'],
            [$account->purpose, 'admin.account.details', $account->id],
            ['Редагування'],
          ], true)
        ]);      
      }
    
      public function postUpdate($request, $response) {
    
        $validation = $this->validator->validate($request, [
          'purpose' => Validator::notVoid(),
        ]);
    
        if ($validation->failed()) {
          $this->flash->addMessage('message', 'Помилка редагування реквізиту');
          return $response->withRedirect($this->router->pathFor('admin.account.update', [
            'id' => $request->getParam('id'),
          ]));
        }
    
        $account = Account::find($request->getParam('id'));
        $account->update([
          'purpose' => $request->getParam('purpose'),
          'rank' => $request->getParam('rank'),
          'is_actual' => $request->getParam('is_actual')  ? true : false,
        ]);
    
        $this->flash->addMessage('message', 'Реквізит було відредаговано');
    
        return $response->withRedirect($this->router->pathFor('admin.account.details', [
          'id' => $account->id,
        ]));
    
      }
    
      public function postUpload($request, $response) {
        $account = Account::find($request->getParam('id'));
    
        $path = Files::getPath([
          $this->abspath, 'public', 'docs'
        ]);
    
        $files = $request->getUploadedFiles();
        $file = $files['file'];
        $fileName = Files::moveFileRandomName($file, $path);
    
        if ($fileName) {
          $account->src = $fileName;
          $account->save();
          $this->flash->addMessage('message', 'Файл було завантажено');   
        } else {
           $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
        }
        return $response->withRedirect($this->router->pathFor('admin.account.details', [
          'id' => $account->id,    
        ]));
      }
    
      public function getCreate($request, $response) {
        return $this->view->render($response, 'admin/account/create.twig', [
          'breadcrumbs' => Pages::breadcrumbs([
            ['Реквізити для оплати', 'admin.accounts'],
            ['Створення'],
          ], true)
        ]);      
      }
    
      public function postCreate($request, $response) {
    
        $validation = $this->validator->validate($request, [
          'purpose' => Validator::notVoid(),
        ]);
    
        if ($validation->failed()) {
          $this->flash->addMessage('message', 'Помилка створення реквізиту');
          return $response->withRedirect($this->router->pathFor('admin.account.create'));
        }
    
        Account::create([
          'purpose' => $request->getParam('purpose'),
          'rank' => $request->getParam('rank'),
          'is_actual' => $request->getParam('is_actual')  ? true : false,
        ]);
    
        $id = Account::max('id');
    
        $this->flash->addMessage('message', 'Реквізит було створено');
    
        return $response->withRedirect($this->router->pathFor('admin.account.details', [
          'id' => $id,
        ]));
    
      }
    
      public function removeFile($request, $response, $args) {
        $file = Files::getPath([
          $this->abspath, 'public', 'docs', $args['file']
        ]);
    
        if (unlink($file)) {
          $this->flash->addMessage('message', 'Файл було видалено');
          $account = Account::find($args['id']);
          $account->src = null;
          $account->save();
        } else {
          $this->flash->addMessage('message', 'Помилка видалення файлу');
        }
        
        return $response->withRedirect($this->router->pathFor('admin.account.details', [
          'id' => $args['id'], 
        ]));
        
    }
    
    

}