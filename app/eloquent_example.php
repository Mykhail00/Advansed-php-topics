<?php

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../eloquent.php';

$invoiceId = 7;

//// Call query builder directly
//$invoices = Capsule::table('invoices')->get();

Invoice::query()->where('id', $invoiceId)->update(['status' => InvoiceStatus::Paid]);

Invoice::query()->where('status', InvoiceStatus::Paid)->get()->each(function(Invoice $invoice) {
   echo $invoice->id . ', ' . $invoice->status->toString() . ', ' . $invoice->created_at->format('m/d/Y') . PHP_EOL;

//    // Access items using hasMany relationship (similar queryBuilder) and build query
//   $invoice->items()->where('description', 'asdfs')->update(['description' => 'foo bar']);

    //Access items as a Collection of models
    $item = $invoice->items->first();

    $item->description = 'Foo Bar';
//    $item->save();
    $invoice->invoice_number = '3';
    $invoice->push();
});


//// Example or creating and persisting models from scratch
//Capsule::connection()->transaction(function () {
//
//    $invoice = new Invoice();
//
//    $invoice->amount = 45;
//    $invoice->invoice_number = '1';
//    $invoice->status = InvoiceStatus::Pending;
//    $invoice->due_date = (new Carbon())->addDays(10);
//
//    $invoice->save();
//
//    $items = [['Item 1', 1, 15], ['Item 2', 3, 5.5], ['Item 3', 4, 3.75]];
//
//    foreach ($items as [$description, $quantity, $unitPrice]) {
//        $item = new InvoiceItem();
//
//        $item->description = $description;
//        $item->quantity = $quantity;
//        $item->unit_price = $unitPrice;
//
////    $item->invoice_id = $invoice->id;
//        $item->invoice()->associate($invoice);
//
//        $item->save();
//    }
//});