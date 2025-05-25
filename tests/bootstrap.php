<?php

require_once "./vendor/autoload.php";

require_once "../../cmsimple/functions.php";

require_once "../plib/classes/CsrfProtector.php";
require_once "../plib/classes/Document2.php";
require_once "../plib/classes/DocumentStore2.php";
require_once "../plib/classes/Request.php";
require_once "../plib/classes/Response.php";
require_once "../plib/classes/SystemChecker.php";
require_once "../plib/classes/Url.php";
require_once "../plib/classes/View.php";
require_once "../plib/classes/FakeRequest.php";
require_once "../plib/classes/FakeSystemChecker.php";

require_once "./classes/dto/ChartDto.php";
require_once "./classes/model/Chart.php";
require_once "./classes/model/Dataset.php";
require_once "./classes/model/PowerChart.php";
require_once "./classes/Configurator.php";
require_once "./classes/Dic.php";
require_once "./classes/ChartAdminCommand.php";
require_once "./classes/ChartCommand.php";
require_once "./classes/InfoCommand.php";

const CMSIMPLE_XH_VERSION = "1.8";
