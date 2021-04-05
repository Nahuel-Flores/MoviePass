<?php
require_once(VIEWS_PATH . "header.php");
require_once(VIEWS_PATH . "nav.php");
?>
<main class="container">
    <h2 class="title_">Movies</h2>


    <form action="<?php echo FRONT_ROOT ?>Projection/updateMoviesList" method="post">
        <input type="hidden" value="<?php echo $roomId ?>" name="hehe">
        <button class="button-a" type="submit">Update movies database</button>
    </form>
    <section class="movie_filter">
        <dl class="dropdown">
            <dt>
                <a>
                    <span class="hida">Genres</span>
                    <div class="multiselct"></div>
                    <div class="fas fa-chevron-down"></div>
                </a>
            </dt>
            <dd>
                <div class="mutliSelect">
                    <ul>
                        <?php
                        foreach ($genres as $gen) {
                            $g = $gen->getName() ?>
                            <label for="<?php echo $gen->getId() ?>"><?php echo $g ?>
                                <li><input class="GenreChk" type='checkbox' name="genres[]" id="<?php echo $gen->getId() ?>" value="<?php echo $g ?>"></li>
                            </label>
                        <?php } ?>
                    </ul>
                </div>
            </dd>
        </dl>
        <button id="filerButton" type="button" class="button-a">Filter</button>
        <button type="button" id="resetBtn" class="button-a">Clear</button>


    </section>
    <input id="searchInput" class="form-control form-control-sm" type="text" placeholder="Search" aria-label="Search" name="search">

    <div class="container_movies">
        <div class="row" id="moviesResponse">
            <?php
            foreach ($movieList as  $value) { ?>
                <div class="col-md-2">
                    <button type="button" class="btn" onClick="dataChange(<?php echo "'" . $value->getPoster() . "','" . addslashes($value->getTitle()) . "','" . addslashes($value->getSynopsis()) . "','" . $value->getId() . "'" ?>);" data-id="<?php echo $value->getId() ?>" data-toggle="modal" data-target=".movie">

                        <figure class="figure">
                            <img class="figure-img img-fluid rounded" src="<?php echo "https://image.tmdb.org/t/p/w154" . $value->getPoster() ?>" width=150>
                            <figcaption class="figure-caption"><?php echo $value->getTitle() ?></figcaption>
                        </figure>

                    </button>
                </div>
                <br>

            <?php } ?>
        </div>
    </div>
</main>


<div class="modal fade movie" id="" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-c">
            <div class="media">
                <img id="imgModal" class="align-self-center mr-3" src="" width="60%">
                <div class="media-body">
                    <br>
                    <h5 class="mt-0" id="modalTitle"></h5>

                    <p id="modalSyn" class="movie_descript"></p>
                    <br><br>
                    <form action="<?php echo FRONT_ROOT ?>Purchase/showMovieStats">
                        <input type="hidden" id="idMovieStats" name="movieIdStats" value="">
                        <button type="submit" class="button-a">View stats</button>
                    </form>
                    <form id="addProjForm" action="<?php echo FRONT_ROOT ?>Projection/add" method="post">
                        <input id="RoomIdInput" type="hidden" name="roomId" value="<?php echo $roomId ?>">
                        <input id="movieIdInput" type="hidden" name="movie_id" id="projection_movie" value="">
                        <input class="form-control" id="projDateInput" type="date" name="projection_date" id="projection_date" min=<?php echo date("Y-m-d") ?> value=<?php echo date("Y-m-d") ?> required>
                        <input class="form-control" id="projTimeInput" type="time" name="projection_time" id="projection_time" required>
                        <button id="submitProj" type="submit" class="button-a">Save Projection</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modal-msg" class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-a" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="<?php echo JS_PATH ?>bootstrap.js"></script>
<script src="<?php echo JS_PATH ?>dataChange.js"></script>
<script src="<?php echo JS_PATH ?>moviesFilter.js"></script>
<script src="<?php echo JS_PATH ?>moviesSearch.js"></script>
<script src="<?php echo JS_PATH ?>projValidate.js"></script>
<script src="<?php echo JS_PATH ?>genres_dropdown.js"></script>
<?php require_once(VIEWS_PATH . "footer.php") ?>