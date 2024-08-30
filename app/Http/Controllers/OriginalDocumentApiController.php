<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Jobs\OrderOriginalDocumentsJob;
use App\Order;
use App\OriginalDocument;

class OriginalDocumentApiController extends Controller
{
    public function index(Request $request, $order_id)
    {
        $order = Order::find($order_id);


        $original_documents = [];
        $original_documents['draft_bl']               = $order->original_documents()->where('original_documents_type_id',  1)->first();
        $original_documents['draft_comercial']        = $order->original_documents()->where('original_documents_type_id',  2)->first();
        $original_documents['packing_list']           = $order->original_documents()->where('original_documents_type_id',  3)->first();
        $original_documents['certificate_origin']     = $order->original_documents()->where('original_documents_type_id',  4)->first();
        $original_documents['certificate_fumigation'] = $order->original_documents()->where('original_documents_type_id',  5)->first();
        $original_documents['certificate_quality']    = $order->original_documents()->where('original_documents_type_id',  6)->first();
        $original_documents['certificate_weight']     = $order->original_documents()->where('original_documents_type_id',  7)->first();
        $original_documents['certificate_seguro']     = $order->original_documents()->where('original_documents_type_id',  8)->first();
        $original_documents['certificate_phyto']      = $order->original_documents()->where('original_documents_type_id',  9)->first();
        $original_documents['report']                 = $order->original_documents()->where('original_documents_type_id', 10)->first();
        $original_documents['health_certificate']     = $order->original_documents()->where('original_documents_type_id', 12)->first();
        $original_documents['non_gmo_certificate']    = $order->original_documents()->where('original_documents_type_id', 13)->first();

        $original_documents['draft_bl']               = $original_documents['draft_bl']               ? $original_documents['draft_bl']->url : null;
        $original_documents['draft_comercial']        = $original_documents['draft_comercial']        ? $original_documents['draft_comercial']->url : null;
        $original_documents['packing_list']           = $original_documents['packing_list']           ? $original_documents['packing_list']->url : null;
        $original_documents['certificate_origin']     = $original_documents['certificate_origin']     ? $original_documents['certificate_origin']->url : null;
        $original_documents['certificate_fumigation'] = $original_documents['certificate_fumigation'] ? $original_documents['certificate_fumigation']->url : null;
        $original_documents['certificate_quality']    = $original_documents['certificate_quality']    ? $original_documents['certificate_quality']->url : null;
        $original_documents['certificate_weight']     = $original_documents['certificate_weight']     ? $original_documents['certificate_weight']->url : null;
        $original_documents['certificate_seguro']     = $original_documents['certificate_seguro']     ? $original_documents['certificate_seguro']->url : null;
        $original_documents['certificate_phyto']      = $original_documents['certificate_phyto']      ? $original_documents['certificate_phyto']->url : null;
        $original_documents['report']                 = $original_documents['report']                 ? $original_documents['report']->url : null;
        $original_documents['health_certificate']     = $original_documents['health_certificate']     ? $original_documents['health_certificate']->url : null;
        $original_documents['non_gmo_certificate']    = $original_documents['non_gmo_certificate']    ? $original_documents['non_gmo_certificate']->url : null;

        $original_documents['others'] = array();
        $others = \App\OriginalDocument::where('order_id', $order_id)->where('original_documents_type_id', 11)->get();

        foreach ($others as $other)
            $original_documents['others'][] = $other->url;

        return response()->json($original_documents);
    }

    protected function buildFile($url, $type, $status, $order_id, $new = false)
    {
        $file = OriginalDocument::where('order_id', $order_id)->where('original_documents_type_id', $type)->first();

        if (!$file || $new)
            $file = new OriginalDocument();

        // if(!$url) return;

        if (is_string($url))
            $file->url = $url;
        else
            $file->url = $url['url'];

        $file->original_documents_status_id = $status;
        $file->original_documents_type_id   = $type;
        $file->order_id = $order_id;

        $file->save();

        return $file;
    }

    public function put(Request $request, $order_id)
    {
        $order = Order::find($order_id);

        var_dump($request->all());
        exit;

        $draft_bl               = $this->buildFile($request->input('draft_bl'),                1, 2, $order_id);
        $draft_comercial        = $this->buildFile($request->input('draft_comercial'),         2, 2, $order_id);
        $packing_list           = $this->buildFile($request->input('packing_list'),            3, 2, $order_id);
        $certificate_origin     = $this->buildFile($request->input('certificate_origin'),      4, 2, $order_id);
        $certificate_fumigation = $this->buildFile($request->input('certificate_fumigation'),  5, 2, $order_id);
        $certificate_quality    = $this->buildFile($request->input('certificate_quality'),     6, 2, $order_id);
        $certificate_weight     = $this->buildFile($request->input('certificate_weight'),      7, 2, $order_id);
        $certificate_seguro     = $this->buildFile($request->input('certificate_seguro'),      8, 2, $order_id);
        $certificate_phyto      = $this->buildFile($request->input('certificate_phyto'),       9, 2, $order_id);
        $report                 = $this->buildFile($request->input('report'),                 10, 2, $order_id);
        $health_certificate     = $this->buildFile($request->input('health_certificate'),     12, 2, $order_id);
        $non_gmo_certificate    = $this->buildFile($request->input('non_gmo_certificate'),    13, 2, $order_id);

        $others = OriginalDocument::where('order_id', $order_id)->where('original_documents_type_id', 11)->get();

        foreach ($others as $other) $other->delete();

        foreach ($request->input('others') as $other) {
            $others = $this->buildFile($other, 11, 2, $order_id, true);
        }


        if ($request->has('sending_emails') && in_array('original_documents', $request->input('sending_emails'))) {
            $this->dispatch(new OrderOriginalDocumentsJob($order));
        }


        return response()->json(['success' => true]);
    }
}
