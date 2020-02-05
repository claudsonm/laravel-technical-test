<?php

namespace App\Handlers;

use App\Order;

class ShipordersXmlHandler extends Handler
{
    protected int $successCount = 0;

    protected int $errorCount = 0;

    /**
     * Handles the XML content processing persisting into the database.
     *
     * @return $this
     */
    public function handle(): Handler
    {
        foreach ($this->content->children() as $pendingOrder) {
            try {
                $order = Order::create([
                    'id' => $pendingOrder->orderid,
                    'destination' => $pendingOrder->shipto->name,
                    'address' => $pendingOrder->shipto->address,
                    'city' => $pendingOrder->shipto->city,
                    'country' => $pendingOrder->shipto->country,
                    'person_id' => $pendingOrder->orderperson,
                ]);

                foreach ($pendingOrder->items->children() as $item) {
                    $order->items()->create([
                        'name' => $item->title,
                        'notes' => $item->note,
                        'quantity' => $item->quantity,
                        'price' => (float) $item->price,
                    ]);
                }

                $this->successCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
            }
        }

        return $this;
    }

    public function getOutput(): array
    {
        $message = 'File processed: '.$this->successCount.' new orders imported and '.$this->errorCount.' orders with errors.';
        $level = $this->errorCount > 0 ? 'warning' : 'success';

        return [$message, $level];
    }
}
