<?php

namespace App\Http\Controllers\Admin;

use App\Restaurant;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RestaurantsController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        return view('admin.restaurants.list');
    }

    public function anyData()
    {
        return \DataTables::of(Restaurant::getMeAllWithTheGivenStatus('current_status')->with('manager')->select('restaurants.*'))
            ->editColumn('photo', function (Restaurant $restaurant) {
                return \Html::link(
                    $restaurant->photo,
                    \Html::image(
                        $restaurant->photo,
                        'Avatar',
                        [
                            'class' => 'img-circle',
                            'style' => 'height: 50px !important; width: 50px !important;'
                        ]
                    ),
                    [
                        'data-lightbox' => 'photo-restaurants',
                        'data-title' => $restaurant->name
                    ], null, false);
            })->editColumn('address', function (Restaurant $restaurant) {
                return '<div style="width: 150px !important; white-space: normal !important; word-wrap: break-word !important;">' . $restaurant->address . '</div>';
            })->addColumn('contact_info', function (Restaurant $restaurant) {
                $phone = $restaurant->phone ? '<h5>Phone:</h5><a href="tel:' . $restaurant->phone . '">' . $restaurant->phone . '</a>' : '-';
                $email = $restaurant->email ? '<h5>E-mail Address:</h5><a href="mailto:' . $restaurant->email . '">' . $restaurant->email . '</a>' : '-';
                return $phone . $email;
            })->addColumn('manager_account', function (Restaurant $restaurant) {
                $manager = $restaurant->manager;
                return $manager->fullName();
            })->editColumn('price_range', function (Restaurant $restaurant) {
                return str_repeat('$',$restaurant->price_range);
            })->editColumn('is_active', function (Restaurant $restaurant) {
                return '<div class="switch panel-switch-btn">
                            <label>De-active<input data-url="' . route('admin.restaurants.update', ['restaurant' => $restaurant->id]) . '" type="checkbox" ' . ($restaurant->is_active ? 'checked' : '') . ' name="is_active" /><span class="lever switch-col-teal"></span>Active</label>
                        </div>';
            })->editColumn('current_status', function (Restaurant $restaurant) {
                switch ($restaurant->current_status) {
                    case 1:
                        $status = '<b class="text-success">Open</b>';
                        break;
                    case 3:
                        $status = '<b class="text-primary">Opening Soon</b>';
                        break;
                    case 4:
                        $status = '<b class="text-warning">Closing Soon</b>';
                        break;
                    default:
                        $status = '<b class="text-danger">Close</b>';
                        break;
                }
                return $status;
            })->addColumn('editAction', function (Restaurant $restaurant) {
                return '<a href="' . route('admin.restaurants.edit', ['restaurant' => $restaurant->id]) . '" data-toggle="modal" data-target="#defaultModal" class="btn btn-primary waves-effect btn-xs"><i class="material-icons">edit</i></a>';
            })->addColumn('deleteAction', function (Restaurant $restaurant) {
                return '<a href="' . route('admin.restaurants.destroy', ['restaurant' => $restaurant->id]) . '" data-success-callback="userDeletedSuccess" class="btn btn-danger waves-effect btn-xs confirm-delete"><i class="material-icons">delete</i></a>';
            })->rawColumns([
                'address', 'manager_account', 'current_status', 'contact_info', 'photo', 'is_active', 'editAction', 'deleteAction'
            ])->make(true);
    }
}
