<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Repositories\AuditRepository as Audit;
use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Flash;
use Auth;
use Nayjest\Grids\EloquentDataProvider;

class ProductsController extends Controller
{

    /**
     * @var Product
     */
    protected $product;

    public function __construct(Product $product)
    {
        $this->product  = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enabledOnly = 1)
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-index'));

        $page_title = trans('products/general.page.index.title');
        $page_description = trans('products/general.page.index.description');

        $dataProvider = new EloquentDataProvider(Product::query()->where('is_active', $enabledOnly));
        return view('products.index', compact('dataProvider', 'page_title', 'page_description', 'enabledOnly'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-create'));

        $page_title = trans('products/general.page.create.title');
        $page_description = trans('products/general.page.create.description');

        $product = new Product();

        return view('products.create', compact('product', 'page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-store', ['product_code' => $attributes['product_code']]), $attributes);

        // prepare newProduct, Product instance
        $newProduct = $this->product->create($attributes);
        $newProduct->save($attributes);
        Flash::success( trans('products/general.audit-log.msg-store', ['product_code' => $attributes['product_code']]) );

        return redirect('/products/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = \App\Product::getProductFromId($id);
        if($product != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-show', ['ID' => $product->id]), $product->toArray());

            $page_title = trans('products/general.page.show.title');
            $page_description = trans('products/general.page.show.description', ['name' => $product->name]);

            return view('products.show', compact('product', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('products/general.error.no-data') );
            return redirect('products/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = \App\Product::getProductFromId($id);
        if($product != null){

            Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-edit', ['ID' => $product->id]));

            $page_title = trans('products/general.page.edit.title');
            $page_description = trans('products/general.page.edit.description', ['name' => $product->name]);

            return view('products.edit', compact('product', 'page_title', 'page_description'));

        }else{
            Flash::error( trans('products/general.error.no-data') );
            return redirect('products/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EditProductRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditProductRequest $request, $id)
    {
        $product = $this->product->find($id);
        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-update', ['ID' => $attributes['id']]), $product->toArray());

        $product->update($attributes);
        Flash::success( trans('products/general.audit-log.msg-update', ['ID' => $attributes['id']]) );
        return redirect()->route('products.edit', ['id' => $id]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $product = $this->product->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-enable', ['ID' => $id]), $product->toArray());

        $product->is_active = 1;
        $product->save();

        Flash::success(trans('products/general.status.enabled'));

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        $product = $this->product->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-disabled', ['ID' => $id]), $product->toArray());

        $product->is_active = 0;
        $product->save();

        Flash::success(trans('products/general.status.disabled'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-enabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $product = $this->product->find($user_id);
                $product->is_active = 1;
                $product->save();
            }
            Flash::success(trans('products/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('products/general.status.no-product-selected'));
        }
        return redirect()->back();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        $chkUsers = $request->input('chkUser');

        Audit::log(Auth::user()->id, request()->ip(), trans('products/general.audit-log.category'), trans('products/general.audit-log.msg-disabled-selected'), $chkUsers);

        if (isset($chkUsers))
        {
            foreach ($chkUsers as $user_id)
            {
                $product = $this->product->find($user_id);
                $product->is_active = 0;
                $product->save();
            }
            Flash::success(trans('products/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('products/general.status.no-product-selected'));
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: implement!
    }
}
