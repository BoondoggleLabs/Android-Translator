<?php
require_once "getStrings.php";

$locale = isset($_GET['lang']) ? $_GET['lang'] : "es";
$languageNames = json_decode(
        file_get_contents("lang-selector/data/generated-langdb.json"), true
);
$languageName = $languageNames['languages'][$locale][2];


foreach ($resources as $file => $resource) {
    if (!isset($resources[$file][$locale])) {
        $resources[$file][$locale] = [];
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Translate Forecaster</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script src="lang-selector/jquery.uls.data.js"></script>
    <script src="lang-selector/jquery.uls.data.utils.js"></script>
    <script src="lang-selector/jquery.uls.lcd.js"></script>
    <script src="lang-selector/jquery.uls.languagefilter.js"></script>
    <script src="lang-selector/jquery.uls.regionfilter.js"></script>
    <script src="lang-selector/jquery.uls.core.js"></script>
    <link href="lang-selector/css/jquery.uls.css" rel="stylesheet">
    <link href="lang-selector/css/jquery.uls.grid.css" rel="stylesheet">
    <link href="lang-selector/css/jquery.uls.lcd.css" rel="stylesheet">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script type="text/javascript" src="sisyphus.min.js"></script>
    <style>
        .border-bottom {
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
        }

        .mdl-typography--headline {
            margin-top: 40px;
        }

        .mdl-typography--caption {
            padding-top: 10px;
        }

        .bottom {
            padding-top: 10px;
        }
    </style>
    <script>
        $(function () {
// or you can persist all forms data at one command
            $("form").sisyphus({

                customKeySuffix: "<?=$locale?>",
            });
        });

        $(document).ready(function () {
            $('.uls-trigger').uls({
                onSelect: function (language) {
                    document.location.href = language;
                },
                quickList: ['de', 'es', 'fr'] //FIXME
            });
        });
    </script>
</head>

<body>
<form action="submit.php?lang=<?=$locale?>" id="basic_form" method="post" name="basic_form">
    <!-- Simple header with scrollable tabs. -->
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <!-- Title -->
                <span class="mdl-layout-title">Create a <?=$languageName  ?> translation for Forecaster</span>
                <!--
                <div class="mdl-layout-spacer"></div>
                <span class="mdl-typography--caption mdl-color-text--white">Saved!</span>-->
            </div>
            <!-- Tabs -->
            <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
                <a href="#start" class="mdl-layout__tab is-active">Welcome</a>
                <? foreach ($resources as $file => $unused) { ?>
                    <i class="material-icons bottom">navigate_next</i>
                    <a href="#<?= get_file_as_id($file) ?>"
                       class="mdl-layout__tab"><? echo getNeatFileName($file) ?></a>
                <? } ?>

                <i class="material-icons bottom">navigate_next</i>
                <a href="#finish" class="mdl-layout__tab">Finish</a>
            </div>
        </header>

        <main class="mdl-layout__content">
            <section class="mdl-layout__tab-panel is-active" id="start">
                <div class="page-content">
                    <div class="mdl-grid ">
                        <div class="mdl-layout-spacer"></div>
                        <div class="mdl-cell mdl-cell--10-col">
                            <div class=" mdl-typography--headline">
                                <span class=" mdl-typography--headline" style="margin-right:20px">Thanks for offering to translate to <?= $languageName ?>
                                    !</span>

                                <button class="active uls-trigger mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary"
                                        type="button">Change Language
                                </button>
                            </div>
                            <p style="
	margin-top:20px;">This tool helps create a complete translation of all the text used in Forecaster. To use it, click
                                through the tabs above to view the text. The English is shown on the left and text boxes
                                are available to enter your translation on the right. Notes are sometimes shown below
                                the English to help explain where the text is used - these don't need translating.</p>
                            <p>Anything you enter in the form will be auto-saved! If you get half way through a
                                translation you can leave the website and come back to it later.</p>
                            <p>Once finished you can submit the translation to me in the last page and I'll try and get
                                it released as soon as I can.</p>
                            <p>If you have any questions, or if I can help with anything please email me at: <a
                                        href="mailto:boondogglelabs@gmail.com">boondogglelabs@gmail.com</a></p>
                        </div>
                        <div class="mdl-layout-spacer"></div>
                    </div>
                </div>
            </section>
            <? foreach ($resources as $file => $locales) { ?>
                <section class="mdl-layout__tab-panel" id="<?= get_file_as_id($file) ?>">
                    <div class="page-content">
                        <div class="mdl-grid border-bottom">
                            <div class="mdl-layout-spacer"></div>
                            <div class="mdl-cell mdl-cell--4-col mdl-typography--font-bold">Original (English)</div>
                            <div class="mdl-cell mdl-cell--6-col mdl-typography--font-bold">Translated
                                (<?= $languageName  ?>)
                            </div>
                            <div class="mdl-layout-spacer"></div>
                        </div>

                        <? foreach ($resources[$file]['default'] as $string) {
                            $formElementName = get_file_as_id($file) . '-' . trim($string['name']); ?>
                            <div class="mdl-grid border-bottom">
                                <div class="mdl-layout-spacer"></div>
                                <div class="mdl-cell mdl-cell--4-col">
                                    <div class=" mdl-typography--body-1"><?=$string?>
                                        <? if (isset($comments[trim($string['name'])])) { ?>
                                            <br>
                                            <div class="mdl-typography--caption mdl-color-text--grey">
                                                Note: <? echo $comments[trim($string['name'])]; ?></div>
                                        <? } ?></div>
                                </div>
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--full-width"
                                         style="padding: 0px;">
                                        <textarea class="mdl-textfield__input " type="text" rows="1"
                                                  id="<?= $formElementName ?>" name="<?= $formElementName ?>"
                                                  style="width:100%; font-size:14px;"><?= find_node_by_name($resources[$file][$locale], trim($string['name'])) ?></textarea>
                                        <label class="mdl-textfield__label"
                                               for="<?= $formElementName ?>">Translation...</label>
                                    </div>
                                </div>
                                <div class="mdl-layout-spacer"></div>
                            </div>

                        <? } ?>

                    </div>
                </section>
            <? } ?>
            <section class="mdl-layout__tab-panel" id="finish">
                <div class="page-content">
                    <div class="mdl-grid ">
                        <div class="mdl-layout-spacer"></div>
                        <div class="mdl-cell mdl-cell--10-col">
                            <div class=" mdl-typography--headline">Nearly done!</div>
                            <p>Clicking submit will save your translations and email them to me. I'll add them into the
                                next release of the app.</p>
                            <div class="mdl-textfield mdl-js-textfield" style=" width: 50%">
                                <input class="mdl-textfield__input " type="text" id="email_address" name="email_address"
                                       style="font-size:14px;">
                                <label class="mdl-textfield__label" for="email_address">Enter your email address for
                                    updates on the translation (optional)</label>
                            </div>
                            <br>

                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary"
                                    type="submit"> Submit
                            </button>
                        </div>
                        <div class="mdl-layout-spacer"></div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</form>
</body>
</html>
