<?php

namespace Wardenyarn\MawiApi;

use Wardenyarn\MawiApi\Entities\Product;

trait Products
{
    public function getProducts(array $params = [])
    {
        return $this->getAll('/integration/xml/products', 'product', $params, Product::class);
    }

    public function getProduct(int $id)
    {
        return $this->apiCall('/integration/xml/product', ['id' => $id], Product::class);
    }

    public function getShippedProducts(array $params = [])
    {
        return $this->getAll('/integration/xml/shippedProducts', 'item', $params);
    }

    /** Warning! Returns All items at once */
    public function getUnshippedProducts(array $params = [])
    {
        return $this->getAll('/integration/xml/unshippedItems', 'item', $params);
    }

    public function setProduct($productType, array $params = [])
    {
        $params['type'] = $productType;
        
        return $this->apiCall('/integration/set/product', $params);
    }

    public function editProduct(int $productId, array $params = [])
    {
        $params['id'] = $productId;
        
        return $this->apiCall('/integration/set/product', $params);
    }

    public function getShippings(array $params = [])
    {
        return $this->getAll('/integration/xml/shippings', 'shipping', $params);
    }

    public function getShipping(int $id)
    {
        return $this->apiCall('/integration/xml/shipping', ['id' => $id]);
    }

    public function getProductCategories()
    {
        return $this->apiCall('/integration/xml/productCategories')->productCategory;
    }
}