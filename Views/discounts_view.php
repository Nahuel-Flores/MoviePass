<?php
require_once(VIEWS_PATH . "header.php");
require_once(VIEWS_PATH . "nav.php");
?>

<?php
$dis = [];
foreach ($discounts as $discount) {
    $dis[$discount->getCreditAccount()->getCompany()] = $discount;
}
?>

<main class="container">
    <form class="flexcont" action="<?php echo FRONT_ROOT ?>Discount/showModifyDiscounts" method="get">
        <input class="form-control" type="date" name="date" id="date" value="<?php echo $date ?>">
        <button class="button-a" type="submit">Search</button>
    </form>

    <section class="flexcont">
    <div class="flexDiscount">
        <h2>American Express</h2>
        <form action="<?php echo FRONT_ROOT ?>Discount/add" method="post">
            <input class="form-control" type="text" name="percent" value="<?php echo isset($dis["American Express"]) ? $dis["American Express"]->getPercent() : "0" ?>">
            <input type="hidden" name="date" value=<?php echo $date ?>>
            <input  type="hidden" name="creditAccountId" value="3">
            <button class="button-a" type="submit">Save</button>
        </form>
    </div>

    <div class="flexDiscount">
        <h2>Visa</h2>
        <form action="<?php echo FRONT_ROOT ?>Discount/add" method="post">
            <input class="form-control" type="text" name="percent" value="<?php echo isset($dis["Visa"]) ? $dis["Visa"]->getPercent() : "0" ?>">
            <input type="hidden" name="date" value=<?php echo $date ?>>
            <input type="hidden" name="creditAccountId" value="1">
            <button class="button-a" type="submit">Save</button>
        </form>
    </div>

    <div class="flexDiscount">
        <h2>Mastercard</h2>
        <form action="<?php echo FRONT_ROOT ?>Discount/add" method="post">
            <input class="form-control" type="text" name="percent" value="<?php echo isset($dis["Mastercard"]) ? $dis["Mastercard"]->getPercent() : "0" ?>">
            <input type="hidden" name="date" value=<?php echo $date ?>>
            <input type="hidden" name="creditAccountId" value="2">
            <button class="button-a" type="submit">Save</button>
        </form>
    </div>
    </section>
</main>

<?php require_once(VIEWS_PATH . "footer.php") ?>