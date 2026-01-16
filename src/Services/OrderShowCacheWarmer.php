<?php

namespace IlBronza\Products\Services;

use IlBronza\Products\Models\Order;
use Illuminate\Support\Facades\Cache;

class OrderShowCacheWarmer
{
    protected $fields = [
        'closed',
        'invoice_done',
        'total_reimbursements_cost',
        'total_reimbursements_revenue',
        'total_vehicles_cost',
        'total_vehicles_revenue',
        'total_operators_cost',
        'total_operators_revenue',
        'total_daily_allowances_cost',
        'total_daily_allowances_revenue',
        'total_hotels_cost',
        'total_hotels_revenue',
        'total_rents_cost',
        'total_rents_revenue',
        'tot_ricavo',
        'total_costs',
        'total_proposal',
        'total_gain',
        'percentage_gain',
        'calculated_production_days',
        'calculated_event_days',
        'total_reimbursements_cost',
        'total_vehicles_cost',
        'total_operators_cost',
        'total_daily_allowances_cost',
        'total_hotels_cost',
        'total_rents_cost',
        'total_costs',
        'total_proposal',
        'total_gain',
        'percentage_gain',
    ];

    public function warmFieldset($fieldset)
    {
        foreach($fieldset['fields'] as $field => $parameters)
            $this->order->$field;

        foreach($fieldset['fieldsets'] ?? [] as $_fieldset)
            $this->warmFieldset($_fieldset);
    }

    public function warm(Order $order): void
    {
        $this->order = $order;

        Cache::remember(
            $order->cacheKey('OrderqdShowCacheWarmer'),
            3600,
            function () use ($order)
            {
                foreach($this->fields as $field)
                    $order->$field;

                foreach($order->operatorRows as $row)
                {
                    $row->has_hotel_room;
                    $row->has_vehicle_seat;
                    $row->quantity_on_total;
                    $row->calculated_cost_company;
                    $row->calculated_cost_gross_operator_total;
                    $row->calculated_daily_allowance_total;
                    $row->gross_and_allowance_total;
                    $row->calculated_row_total;
                    $row->calculated_cost_company_total;
                    $row->confirmed;
                    $row->convocated_when;
                    $row->convocated_where;
                }

                foreach($order->vehicleRows as $row)
                {
                    $row->calculated_cost_company;
                    $row->calculated_toll;
                    $row->calculated_row_total;
                }

                foreach($order->rentRows as $row)
                {
                    $row->calculated_cost_company;
                    $row->client_price;
                    $row->quantity;
                    $row->calculated_cost_company_total;
                    $row->calculated_row_total;
                    $row->client_price_total;
                }

                foreach($order->hotelRows as $row)
                {
                    $row->first_guest;
                    $row->second_guest;
                    $row->vat_calculated_cost_company;
                    $row->vat_calculated_cost_company_total;
                    $row->vat;
                    $row->calculated_cost_company;
                    $row->cost_company_approver;
                    $row->calculated_cost_company_total;
                    $row->calculated_row_total;
                }

                foreach($order->reimbursementRows as $row)
                {
                    $row->reimbursement_operator;
                    $row->calculated_cost_company;
                    $row->calculated_toll;
                    $row->quantity;
                    $row->calculated_cost_company_total;
                    $row->reimbursement_from_place;
                    $row->reimbursement_to_place;
                    $row->reimbursement_status;
                    $row->calculated_row_total;
                }

                foreach($order->operatorVatRows as $row)
                {
                    $row->reimbursement_annotations;
                    $row->invoice_number;
                    $row->invoice_date;
                }

                foreach($order->rentVatRows as $row)
                {
                    $row->reimbursement_annotations;
                    $row->invoice_number;
                    $row->invoice_date;
                }

                foreach($order->hotelVatRows as $row)
                {
                    $row->reimbursement_annotations;
                    $row->invoice_number;
                    $row->invoice_date;
                }

                foreach($order->reimbursementVatRows as $row)
                {
                    $row->reimbursement_annotations;
                    $row->invoice_number;
                    $row->invoice_date;
                }

                foreach($order->vatRows as $row)
                {
                    $row->reimbursement_annotations;
                    $row->invoice_number;
                    $row->invoice_date;
                }

                return true;
            });
    }

    protected function cacheKey(Order $order): string
    {
        return "orders.show:{$order->getKey()}";
    }
}