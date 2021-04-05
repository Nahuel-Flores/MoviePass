<?php
require_once(VIEWS_PATH . "header.php");
require_once(VIEWS_PATH . "nav.php");
?>

<main class="container-form">
    <h2 class="title_">Earnings</h2>
    <div class="form-group formDiv">
    <input type="hidden" id="cinemaId" name="cinemaId" value="<?php echo $cinemaId ?>">
    <input type="date" class="form-control" id="firstDate" name="firstDate">
    <input type="date" class="form-control" id="secondDate" name="secondDate">
    <label for="" class="form-control text-center" id="result" ></label>
    <button type="button" class="button-a" id="btn">Search</button>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="<?php echo JS_PATH ?>cinemaStats.js"></script>
<?php require_once(VIEWS_PATH . "footer.php") ?>