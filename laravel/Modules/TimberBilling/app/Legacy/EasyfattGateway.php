<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Legacy;

interface EasyfattGateway
{
    /**
     * @return array<int, EasyfattCustomerData>
     */
    public function fetchCustomers(): array;

    /**
     * @return array<int, EasyfattProductData>
     */
    public function fetchProducts(): array;
}
