<?php
try {
    require "php/includes/db.php";

    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    } else {
        $type = 'id';
    }

    if (isset($_GET['sort'])) {
        $status = $_GET['sort'];
    } else {
        $status = 'DESC';
    }


    $query = $_GET['query'];
    if (isset($_GET['query'])) {
        $projects = R::findAll('projects', "title LIKE '%$query%'");
        if (isset($_GET['type'])) {
            $projects = R::findAll('projects', ("title LIKE '%$query%' ORDER BY " . $type . ' ' . $status));
        }
    } else {
        $projects = R::findAll('projects', ('ORDER BY ' . $type . ' ' . $status));
    }
    $id = $_COOKIE['id'];

    $status == 'DESC' ? $status = 'ASC' : $status = 'DESC';


    $test = str_split($_SERVER['REQUEST_URI']);
    $e = array_search('q', $test);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/list_objects.css" />
        <link rel="stylesheet" href="css/sidebar.css" />
        <title>Document</title>

    </head>

    <body>
        <?php include './php/components/sidebar.php' ?>
        <main>

            <section class="search">
                <h2 class="search__title">Поиск проектов</h2>
                <form class="search-form">
                    <input type="text" class="search-form__input" name="query" placeholder="Введите текст" />
                    <img src="img/search/filter.svg" alt="search__icon" class="search-form__img icon__filter">
                </form>
            </section>


            <section class="search-sort">
                <h2 class="search-sort__title">Сортировать</h2>
                <div class="search-sort__block">
                    <?php
                    if ($e) {
                    ?>

                        <a href='?query=<?= $_GET['query'] ?>&&type=id&&sort=<?= $status ?>'><button class="search-sort__btn" type="submit">По дате <img class="search-sort__status" src="img/search/Forward.svg" /> </button></a>
                        <a href='?query=<?= $_GET['query'] ?>&&type=title&&sort=<?= $status ?>'><button class="search-sort__btn" type="submit">По алфавиту <img class="search-sort__status" src="img/search/Forward.svg" /> </button></a>
                        <a href='?query=<?= $_GET['query'] ?>&&type=category&&sort=<?= $status ?>'><button class="search-sort__btn" type="submit">По тегам <img class="search-sort__status" src="img/search/Forward.svg" /> </button></a>

                    <?php
                    } else {
                    ?>

                        <a href='?type=id&&sort=<?= $status ?>'><button class="search-sort__btn" type="submit" value="По дате">По дате <img class="search-sort__status" src="img/search/Forward.svg" /></button></a>
                        <a href='?type=title&&sort=<?= $status ?>'><button class="search-sort__btn" type="submit">По алфавиту <img class="search-sort__status" src="img/search/Forward.svg" /></button></a>
                        <a href='?type=category&&sort=<?= $status ?>'><button class="search-sort__btn" type="submit">По тегам <img class="search-sort__status" src="img/search/Forward.svg" /></button></a>

                    <?php
                    }
                    ?>
                </div>
            </section>




            <?php
            foreach ($projects as $project) {
            ?>
                <!-- <div>
                    <h4><?= $project['title'] ?></h4>
                    <span><?= $project->creator_name ?></span> <br>
                    <span><?= $project->category ?></span> <br>
                    <a href="project?id=<?= $project->id ?>">View project</a>
                    <a href="update_objects?id=<?= $project->id ?>">Update data</a>
                    <a href="php/delete?id=<?= $project->id ?>">Delete data</a>
                </div> -->

                <section class="section section-card">
                    <div class="container">
                        <div class="section-card__content">
                            <img src="img/card__img.png" alt="logo" class="section-card__img">
                            <div class="content__text">
                                <h2 class="section-card__title"><?= $project['title'] ?></h2>
                                <span class="section-card__description"><?= $project['descr'] ?></span>
                            </div>
                            <div class="content__tags">
                                <div class="block">
                                    <?php

                                    $tags = explode(', ', $project->category);
                                    
                                    foreach ($tags as $tag) {
                                        ?>
                                        <span class="section-card__tag"><?=$tag?></span>
                                        <?php
                                    }

                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="section-card__controls">
                            <button class="section-card__button-join">Вступить</button>
                            <a href="project?id=<?= $project->id ?>"><button class="section-card__button-view">Посмотреть проект</button></a>
                            <span class="section-card__date">16 Апреля</span>
                        </div>
                    </div>
                </section>
        <?php
            }
        } catch (Throwable $e) {
            echo $e;
        }
        ?>
        </main>
    </body>
    <script>

        const button = document.querySelectorAll('.search-sort__btn');
        const icon_status = document.querySelectorAll('.search-sort__status');
        const descr = document.querySelectorAll('.section-card__description');


        if(location.toString().indexOf('id') !== -1) {
            icon_status[0].style.display = 'block';
            button[0].style.background = "#fff";
            button[0].style.color = "#8075FF";
            if(location.toString().indexOf('ASC') !== -1) {
                icon_status[0].style.transform = 'rotateY(0deg)';
                icon_status[0].style.paddingRight = '20px';
            } 

            if(location.toString().indexOf('DESC') !== -1) {
                icon_status[0].style.transform = '';
                icon_status[0].style.paddingRight = '20px';
            } 
        } 

        if(location.toString().indexOf('title') !== -1) {
            icon_status[1].style.display = 'block';
            button[1].style.background = "#fff";
            button[1].style.color = "#8075FF";
            button[1].style.paddingLeft = "20px";
            if(location.toString().indexOf('ASC') !== -1) {
                icon_status[1].style.transform = 'rotateY(0deg)';
                icon_status[1].style.paddingRight = '20px';
            } 

            if(location.toString().indexOf('DESC') !== -1) {
                icon_status[1].style.transform = '';
                icon_status[1].style.paddingRight = '20px';
            } 
        } 

        if(location.toString().indexOf('category') !== -1) {
            icon_status[2].style.display = 'block';
            button[2].style.background = "#fff";
            button[2].style.color = "#8075FF";
            if(location.toString().indexOf('ASC') !== -1) {
                icon_status[2].style.transform = 'rotateY(0deg)';
                icon_status[2].style.paddingRight = '20px';
            } 

            if(location.toString().indexOf('DESC') !== -1) {
                icon_status[2].style.transform = '';
                icon_status[2].style.paddingRight = '20px';
            } 
        } 


        
    </script>
</html>