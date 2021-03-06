<?php

App::uses('AppController', 'Controller');

/**
 * Orders Controller
 *
 * @property Order $Order
 */
class OrdersController extends AppController {

  /**
   * index method
   *
   * @return void
   */
  public function index() {
    $this->Order->recursive = 0;
    $this->set('orders', $this->paginate());
  }

  /**
   * view method
   *
   * @param string $id
   * @return void
   */
  public function view($id = null) {
    $this->Order->id = $id;
    if (!$this->Order->exists()) {
      throw new NotFoundException(__('Invalid order'));
    }
    $this->set('order', $this->Order->read(null, $id));
  }

  /**
   * add method
   *
   * @return void
   */
  public function add($user_id = null) {
    if ($this->request->is('post')) {
      $this->Order->create();
      if ($this->Order->save($this->request->data)) {
        $this->Session->setFlash(__('The order has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The order could not be saved. Please, try again.'));
      }
    }
    $this->request->data['Order']['user_id'] = $user_id;
  }

  public function add_to_user($user_id) {
    if (!$this->request->is('get')) {
      throw new MethodNotAllowedException();
    }

    $this->Order->create();
    if ($this->Order->save(array('user_id' => $user_id))) {
      $this->Session->setFlash(__('The order has been saved'));
      $this->redirect(array('controller' => 'users', 'action' => 'view', $user_id));
    } else {
      $this->Session->setFlash(__('The order could not be saved. Please, try again.'));
    }
  }

  /**
   * edit method
   *
   * @param string $id
   * @return void
   */
  public function edit($id = null) {
    $this->Order->id = $id;
    if (!$this->Order->exists()) {
      throw new NotFoundException(__('Invalid order'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Order->save($this->request->data)) {
        $this->Session->setFlash(__('The order has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The order could not be saved. Please, try again.'));
      }
    } else {
      $this->request->data = $this->Order->read(null, $id);
    }
    $users = $this->Order->User->find('list');
    $this->set(compact('users'));
  }

  /**
   * delete method
   *
   * @param string $id
   * @return void
   */
  public function delete($id = null) {
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException();
    }
    $this->Order->id = $id;
    if (!$this->Order->exists()) {
      throw new NotFoundException(__('Invalid order'));
    }
    if ($this->Order->delete()) {
      $this->Session->setFlash(__('Order deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Order was not deleted'));
    $this->redirect(array('action' => 'index'));
  }

}
