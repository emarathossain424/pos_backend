<?php

class ProductController
{
    public $data;
    public $product;
    public $category;
    public $config;
    public $file;

    public function __construct($data, $config, $file = null)
    {
        $database = new database($config);
        $db = $database->connect();

        $this->product = new Product($db);
        $this->category = new Category($db);
        $this->data = $data;
        $this->config = $config;
        $this->file = $file;
    }

    /**
     * Will store products in database
     * @return false|string
     */
    public function storeProduct()
    {
        $validation = $this->isValidRequest();

        try {
            $image = $this->storeProductImage();

            if (!$validation['is_validated']) {
                return validationErrorMessages(500, $validation['errors']);
            } else {
                $this->product->name = $this->data->name;
                $this->product->sku = $this->data->sku;
                $this->product->price = round($this->data->price, 2);
                $this->product->category = $this->data->category;
                $this->product->description = $this->data->description;
                if ($image) {
                    $this->product->image = $image;
                } else {
                    $this->product->image = null;
                }
                $response = $this->product->create();
                if ($response) {
                    return successMessagesWithoutData("Product creation successful");
                } else {
                    return errorMessages(500, ['error' => "Product creation unsuccessful"]);
                }
            }
        } catch (Exception $e) {
            $this->deleteUploadedFile();
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, 'Product creation unsuccessful');
        }
    }

    /**
     * Will update requested product
     * @return false|string
     */
    public function updateProduct(){
        $validation = $this->isValidRequest(true);
        try {
            $image = $this->storeProductImage();

            if (!$validation['is_validated']) {
                return validationErrorMessages(500, $validation['errors']);
            } else {
                $this->product->id = $this->data->product_id;
                $this->product->name = $this->data->name;
                $this->product->sku = $this->data->sku;
                $this->product->price = round($this->data->price,2);
                $this->product->category = $this->data->category;
                $this->product->description = $this->data->description;
                if ($image) {
                    $this->product->image = $image;
                } else {
                    $this->product->image = null;
                }
                $response = $this->product->update();
                if ($response) {
                    return successMessagesWithoutData("Product update successful");
                } else {
                    return errorMessages(500, ['error' => "Product update unsuccessful"]);
                }
            }
        } catch (Exception $e) {
            $this->deleteUploadedFile();
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, 'Product update unsuccessful');
        }
    }


    /**
     * Will validate required fields
     * @param  bool  $is_for_update
     * @return array
     */
    public function isValidRequest($is_for_update=false)
    {
        $errors = [];
        if (empty($this->data->name)) {
            $errors['name'] = 'Name field is required';
        }
        if (empty($this->data->price) || !is_numeric($this->data->price)) {
            $errors['price'] = 'Please give a valid product price';
        }

        if (!empty($this->data->sku)) {
            if (!$is_for_update && !$this->product->isUniqueSKU($this->data->sku)) {
                $errors['sku'] = "This SKU is already in use";
            }
        } else {
            $errors['sku'] = 'SKU field is required';
        }
        if (!empty($this->data->category)) {
            if (!$this->category->isValidCategory($this->data->category)) {
                $errors['category'] = "Category not found";
            }
        } else {
            $errors['category'] = 'Category field is required';
        }

        if($is_for_update){
            if (!empty($this->data->product_id)) {
                $response=$this->validateProductId();
                if (!$response['is_validated']) {
                    $errors['product_id'] = "Invalid product id";
                }
            } else {
                $errors['product_id'] = 'Product id field is required';
            }
        }

        if (array_key_exists('product_image', $this->file)) {
            $mime_type = mime_content_type($this->file['product_image']['tmp_name']);
            if (!in_array($mime_type, array('image/jpeg', 'image/png'))) {
                $errors['product_image'] = 'Please upload an image of type jpeg/png';
            }
        }


        if (sizeof($errors) > 0) {
            return [
                'is_validated' => false,
                'errors' => $errors
            ];
        }
        return [
            'is_validated' => true,
        ];
    }

    /**
     * Will return all categories
     * @return false|string
     */
    public function getAllCategories()
    {
        try {
            return successMessages($this->category->readAll());
        } catch (Exception $e) {
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, "Unable to extract categories");
        }
    }

    /**
     * will return unique sku
     * @return string
     */
    public function generateSKU()
    {
        try {
            return successMessages(['sku' => $this->product->createSKU()]);
        } catch (Exception $e) {
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, "Unable to generate sku");
        }
    }

    /**
     * Will upload product image to specific folder
     * @return bool|string
     */
    public function storeProductImage()
    {
        if (array_key_exists('product_image', $this->file)) {
            $target_dir = __DIR__."/../assets/uploads/";
            $image = $this->file['product_image']['name'];
            $path = pathinfo($image);
            $ext = $path['extension'];
            $temp_name = $this->file['product_image']['tmp_name'];
            $path_filename_ext = $target_dir.$this->data->sku.".".$ext;
            $filename_to_store = $this->data->sku.".".$ext;
            move_uploaded_file($temp_name, $path_filename_ext);
            return $filename_to_store;
        }
        return false;
    }

    /**
     * Will remove product image if anything goes wrong while storing products
     */
    public function deleteUploadedFile()
    {
        if (array_key_exists('product_image', $this->file)) {
            $target_dir = __DIR__."/../assets/uploads/";
            $image = $this->file['product_image']['name'];
            $path = pathinfo($image);
            $ext = $path['extension'];
            $path_filename_ext = $target_dir.$this->data->sku.".".$ext;
            unlink($path_filename_ext);
        }
    }

    /**
     * Will return all products
     * @return false|string
     */
    public function getAllProducts()
    {
        try {
            return successMessages($this->product->readAll());
        } catch (Exception $e) {
            $error = ['error'=>$e->getMessage()];
            return errorMessagesForExceptions(500, $error, "Unable to extract products");
        }
    }

    /**
     * Will return requested product details
     * @return false|string
     */
    public function getSingleProducts(){
        $validation = $this->validateProductId();
        if (!$validation['is_validated']) {
            return validationErrorMessages(500, $validation['errors']);
        } else {
            try {
                $this->product->id = $this->data->product_id;
                return successMessages($this->product->readSingle());
            }
            catch (Exception $e){
                $error = ['error'=>$e->getMessage()];
                return errorMessagesForExceptions(500,$error,"Cannot delete requested product");
            }
        }
    }

    /**
     * Will attempt to delete product
     * @return false|string
     */
    public function deleteProduct(){
        $validation = $this->validateProductId();
        if (!$validation['is_validated']) {
            return validationErrorMessages(500, $validation['errors']);
        } else {
            try {
                $this->product->id = $this->data->product_id;
                $result = $this->product->delete();
                if($result){
                    return successMessagesWithoutData("Requested product deleted successfully");
                }
                else{
                    return errorMessages(500,['error'=>"Cannot delete requested product"]);
                }
            }
            catch (Exception $e){
                $error=['error'=>$e->getMessage()];
                return errorMessagesForExceptions(500,$error,"Cannot delete requested product");
            }
        }
    }

    /**
     * Will validate requested product
     * @return array
     */
    public function validateProductId(){
        $errors=[];
        if (empty($this->data->product_id)) {
            $errors['product_id']='product id field is required';
        }
        else{
            if(!$this->product->isValidProduct($this->data->product_id)){
                $errors['product_id']='Invalid product id';
            }
        }
        if(sizeof($errors)>0){
            return [
                'is_validated' => false,
                'errors' => $errors
            ];
        }
        return [
            'is_validated' => true,
        ];
    }
}