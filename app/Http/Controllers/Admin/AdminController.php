<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminRepository;
use Admin;
use App\Http\Requests\Admin\AdminRequest;
use Redirect;

/**
 * Admin Controller
 * This controller is responsible for handling CRUD of Administration Panel
 * 
 * @package     Controllers
 * @author    Den
 */
class AdminController extends Controller {

  /**
   * Admin Repository
   *
   * @var App\Repositories\Admin\AdminRepository
   */
  protected $admin;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(AdminRepository $admin) {
    $this->middleware('authOwl');
    $this->admin = $admin;
  }
 
  /**
   * Display the admin dashboard.
   *
   * @return Response
   */
  public function dashboard() {
    $title = 'Admin - Dashboard';
    $options = [];
    $content = view('admin.dashboard', $options)->render();
    return Admin::view($content, $title);
  }

  /**
   * Change Admin Password
   * 
   * @param AdminRequest $request password change data
   * @return Response
   */
  public function changePassword(AdminRequest $request) {

    $data = $this->admin->changePassword($request);
    if (isset($data)) {
      $content = view('admin.dashboard', $data)->render();
      $title = 'Admin - Dashboard';
      return Admin::view($content, $title);
    } elseif (!$data) {
      return redirect("/");
    }
  }
  
   public function sitemapGen() {
        \Queue::push('\App\Commands\MakeSitemap');
      return Redirect::to('admin')->with('message', 'Задание на создание sitemap отправлено в очередь');
   }

}

