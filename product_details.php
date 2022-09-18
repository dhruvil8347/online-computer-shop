<?php foreach($all_products as $product){ ?> 
<div class="modal fade" id="product<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="productdetails" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ptoductdetails">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-6">
                    <img src="<?php echo $product['image'];?>" class="card-img-top" style="width: 375px;height: 375px;">
                </div>
                <div class="col-md-6">
                    <h4 class="d-inline-block"> <?php echo $product['name']; ?> 
                       </h4>
                   
                    <h4>&#x20B9;<?php echo $product['price']; ?></h4>
                    <h6>Delivery in 4 Days | 
                        <span  class="text-success"> Free<span></h6>
                    <table class="my-3" cellpadding="7">
                        <tr>
                            <th width="150px">Category</th>
                            <td><?php echo $category['category']; ?></td>
                        </tr>
                       
                       
                    </table>

                    <?php if(!$product['quantity']){ ?>

                    <button class="btn btn-danger btn-sm">
                        Out Of Stock
                    </button>
                    
                    <?php }elseif(isset($_SESSION['customer_id'])){
                        $q=$db->prepare("SELECT quantity FROM cart 
                            WHERE product_id=? AND customer_id=?;");
                        $q->execute([$product['id'],$_SESSION['customer_id']]);
                        if($q->fetch()){ ?>
                    
                    <a class="btn btn-dark" href="cart.php">
                        Go To Cart
                    </a>
                    
                    <?php }else{ ?>
                    
                    <a class="btn btn-dark" href="functions.php?id=<?php echo $product['id']; ?>&action=add">
                        Add To Cart</a>
                    
                    <?php } }else{ ?>
                    
                    <a class="btn btn-dark" href="functions.php?
                        id=<?php echo $product['id']; ?>&action=add">
                        Add To Cart</a>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>