<?php

use Models\Cinema;

require_once(VIEWS_PATH . "header.php");
require_once(VIEWS_PATH . "nav.php");
?>
<main class="container">
    <h1 class="title_">Cinemas</h1>
    <div class="custom-scrollbar table-wrapper-scroll-y">
        <input type="text" id="input" onkeyup="myFunction()" class="form-control" placeholder="Search for names..">
        <table id="table" class="table text-center table-hover  table-striped table-cinemas">
            <thead>
                <tr class="th-pointer table-font">
                    <th>Name</th>
                    <th>Province</th>
                    <th>City</th>
                    <th>Address</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(isset($cinemas)){
                foreach ($cinemas as $cine) {
                ?>
                    <tr class="table-font-alt">
                        <td><?php echo $cine->getName() ?></td>
                        <td><?php echo $cine->getProvince()->getName() ?></td>
                        <td><?php echo $cine->getCity()->getName() ?></td>
                        <td><?php echo $cine->getAddress() ?></td>
                        <td>
                            <form action="<?php echo FRONT_ROOT ?>Purchase/showCinemaStats" method="POST">
                                <button name="id" class="btn vinculito" type="submit" value="<?php echo $cine->getId() ?>"><strong>Stats</strong>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="<?php echo FRONT_ROOT ?>Room/showRoom" method="POST">
                                <button name="id" class="btn vinculito" type="submit" value=<?php echo $cine->getId() ?>><strong>Room Admin</strong></button>
                            </form>
                        </td>

                        <td>
                            <button type="button" class="btn" onClick="modifyCinema(<?php echo "'" . $cine->getId() . "','" . $cine->getName() . "','" . $cine->getProvince()->getName() . "','" . $cine->getCity()->getName() . "','"  . $cine->getAddress() . "'"  ?>)" data-id="" data-toggle="modal" data-target="#modify_cinema">
                                <img src="/MoviePass/Views/img/wrench-4x.png" alt="wrench_icon" width="30px">
                            </button>
                        </td>

                        <td>
                            <form action="<?php echo FRONT_ROOT ?>Cinema/remove" method="post">
                                <input type="hidden" name="id" value=<?php echo $cine->getId() ?>>
                                <button type="submit" class="btn">
                                    <img src="/MoviePass/Views/img/trash-4x.png" alt="trash_icon" width="30px" >
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php }} ?>
            </tbody>
        </table>
    </div>
    <?php if(isset($message)&&$message!=""){
        echo "<div class='alert alert-danger'><strong>D:</strong> $message</div>";
    }?>
    <?php 
        if ($_SESSION['userType'] != 3 ) {?> 
              <button class="button-a" data-toggle="modal" data-target="#add_cinema">New cinema</button>  
    <?php } ?> 
    

</main>

<!--ADDING NEW CINEMA MODAL  -->

<div class="modal fade" tabindex="-1" role="dialog" id="add_cinema">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content  modal-c container">

            <div class="modal-header-adding">
                <button class="close" data-dismiss="modal">
                    <span class="close" aria-hidden="true">&times;</span>
                </button>
                <h1 class="modal-title-adding title_">New cinema</h1>
            </div>

            <form action="<?php echo FRONT_ROOT ?>Cinema/add" method="post" class="form-group">
                <label  for="name"><span>Name</span></label>
                <input type="text" name="name" id="name" class="form-control input-cinema" required>
                <label  for="province"><span>Province</span></label>
                <select name="province" class="form-control input-cinema" id="province" required>
                    <?php foreach ($this->provinces as $value) {
                        echo "<option class='select-form'data-id=" . $value->getId() . " value=" . $value->getId() . ">" . $value->getName() . "</option>";
                    } ?>
                </select>
                <label for="City"><span>City</span></label>
                <select name="city" class="form-control input-cinema" id="response" required>
                    <?php foreach ($this->initCities as $c) {
                        $id = $c->getId();
                        $name = $c->getName();
                        echo "<option class='select-form' data-id=$id value='$id'>$name</option>";
                    } ?>
                </select>
                <label  for="address"><span>Address</span></label>
                <input type="address" name="address" id="address" class="form-control input-cinema" required>
                <button class="button-a" type="submit">Register</button>
            </form>
        </div>
    </div>
</div>

<!-- MODAL MODIFY CINEMA  -->

<div class="modal fade " role="document" id="modify_cinema">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-c container">
            <div class="modal-header-adding">
                <button class="close" data-dismiss="modal">
                    <span class="close" aria-hidden="true">&times;</span>
                </button>
                <h1 class="modal-title-adding title_">Modify cinema</h1>
            </div>
            <form action="<?php echo FRONT_ROOT ?>Cinema/modify" method="post" class="form-group">
                
                    <label for="name"><span>Name</span> </label>
                    <input class="form-control input-cinema" name="modalName" type="text" id="modalName" required>
                    <input type="hidden" name="modalCineId" id="modalCineId" required>
                    <label for="province"><span>Province</span></label>

                    <select name="modalProvince" class="form-control input-cinema" id="modalProvince" required>
                        <?php foreach ($this->provinces as $value) {
                            echo "<option class='select-form' data-id=" . $value->getId() . " value=" . $value->getId() . ">" . $value->getName() . "</option>";
                        } ?>
                    </select>

                    <label for="City"><span>City</span></label>

                    <select name="modalCity" class="form-control input-cinema" id="modalCity" value="" required>
                        <?php foreach ($this->initCities as $c) {
                            $id = $c->getId();
                            $name = $c->getName();
                            echo "<option class='select-form' data-id=$id value='$id'>$name</option>";
                        } ?>
                    </select>
                    <label for="Address"><span>Address</span></label>
                    <input type="text" value="" name="modalAddress" id="modalAddress" class="form-control input-cinema" required>
                    <button class="button-a" type="submit">Modify</button>
                
                </form>
            </div>
        </div>
    </div>





<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="<?php echo JS_PATH ?>cinema.table.js"></script>
<script src="<?php echo JS_PATH ?>modifyRegister.js"></script>
<script src="<?php echo JS_PATH ?>bootstrap.js"></script>
<script src="<?php echo JS_PATH ?>location_select.js"></script>
<?php require_once(VIEWS_PATH . "footer.php") ?>