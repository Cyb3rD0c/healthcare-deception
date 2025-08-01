<?php

namespace OpenEMR\Modules\WenoModule;

use OpenEMR\Modules\WenoModule\WenoGlobalConfig;
use OpenEMR\Common\Logging\SystemLogger;
use OpenEMR\Core\Kernel;
use OpenEMR\Events\Globals\GlobalsInitializedEvent;
use OpenEMR\Services\Globals\GlobalSetting;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;
use OpenEMR\Menu\MenuEvent;
use OpenEMR\Events\PatientDemographics\RenderEvent as pRenderEvent;
use OpenEMR\Events\PatientDemographics\RenderPharmacySectionEvent;
use OpenEMR\Events\Patient\PatientBeforeCreatedAuxEvent;
use OpenEMR\Events\Patient\PatientUpdatedEventAux;
use OpenEMR\Modules\WenoModule\Services\SelectedPatientPharmacy;

class Bootstrap
{
    const OPENEMR_GLOBALS_LOCATION = "../../../../globals.php";
    const MODULE_INSTALLATION_PATH = "/interface/modules/custom_modules/oe-module-weno";
    const MODULE_NAME = "";
    const MODULE_MENU_NAME = "Weno";

    /**
     * @var EventDispatcherInterface The object responsible for sending and subscribing to events through the OpenEMR system
     */
    private $eventDispatcher;

    private $moduleDirectoryName;

    /**
     * The OpenEMR Twig Environment
     * @var Environment
     */
    private $twig;

    /**
     * @var WenoGlobalConfig
     */
    private $globalsConfig;

    /**
     * @var SystemLogger
     */
    private $logger;

    private $modulePath;

    /**
     * @var SelectedPatientPharmacy
     */
    private $selectedPatientPharmacy;

    public function subscribeToEvents()
    {
        $this->addGlobalSettings();
        $this->registerMenuItems();
        $this->registerDemographicsEvents();
        $this->demographicsSelectorEvents();
        $this->demographicsDisplaySelectedEvents();
        $this->patientSaveEvents();
        $this->patientUpdateEvents();
    }

    public function __construct(EventDispatcher $dispatcher, ?Kernel $kernel = null)
    {
        if (empty($kernel)) {
            $kernel = new Kernel();
        }
        $this->eventDispatcher = $dispatcher;

        $this->globalsConfig = new WenoGlobalConfig($GLOBALS);
        $this->moduleDirectoryName = basename(dirname(__DIR__));
        $this->modulePath = dirname(__DIR__);
        $this->logger = new SystemLogger();
        $this->selectedPatientPharmacy = new SelectedPatientPharmacy();
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    public function addGlobalWenoSettings(GlobalsInitializedEvent $event)
    {
        $settings = $this->globalsConfig->getGlobalSettingSectionConfiguration();

        $userMode = (array_key_exists('mode', $_GET) && $_GET['mode'] == 'user');

        $service = $event->getGlobalsService();
        $service->addUserSpecificTab(self::MODULE_MENU_NAME);

        foreach ($settings as $key => $config) {
            $value = $GLOBALS[$key] ?? $config['default'];
            if ($userMode) {
                $service->appendToSection(
                    self::MODULE_MENU_NAME,
                    $key,
                    new GlobalSetting(
                        xlt($config['title']),
                        $config['type'],
                        $value,
                        xlt($config['description']),
                        $config['user_setting']
                    )
                );
            } else {
                if ($config['user_setting']) {
                    continue;
                }
                $service->appendToSection(
                    self::MODULE_MENU_NAME,
                    $key,
                    new GlobalSetting(
                        xlt($config['title']),
                        $config['type'],
                        $value,
                        xlt($config['description']),
                        $config['user_setting']
                    )
                );
            }
        }
    }

    public function registerDemographicsEvents()
    {
        $this->eventDispatcher->addListener(pRenderEvent::EVENT_SECTION_LIST_RENDER_BEFORE, [$this, 'renderWenoSection']);
    }

    public function renderWenoSection(pRenderEvent $event)
    {
        $path = __DIR__;
        $path = str_replace("src", "templates", $path);

        $pid = $event->getPid();
        ?>
        <section class="card mb-2">
        <?php
        // Weno expand collapse widget
        $widgetTitle = self::MODULE_MENU_NAME;
        $widgetLabel = "wenocard";
        $widgetButtonLabel = xl("Edit");
        $widgetButtonLink = ""; // "return newEvt();";
        $widgetButtonClass = "d-none";
        $linkMethod = "html";
        $bodyClass = "notab";
        $widgetAuth = false;
        $fixedWidth = false;
        $forceExpandAlways = false;

        expand_collapse_widget(
            $widgetTitle,
            $widgetLabel,
            $widgetButtonLabel,
            $widgetButtonLink,
            $widgetButtonClass,
            $linkMethod,
            $bodyClass,
            $widgetAuth,
            $fixedWidth,
            $forceExpandAlways
        );
        ?>
        
        <div> <?php include $path . "/weno_fragment.php";?> </div>
    </section>
        <?php
    }

    public function addGlobalSettings()
    {
        $this->eventDispatcher->addListener(GlobalsInitializedEvent::EVENT_HANDLE, [$this, 'addGlobalWenoSettings']);
    }

    public function registerMenuItems()
    {
        $this->eventDispatcher->addListener(MenuEvent::MENU_UPDATE, [$this, 'addCustomMenuItem']);
    }

    public function addCustomMenuItem(MenuEvent $event)
    {
        $menu = $event->getMenu();
        //Prescription Log
        $menuItem = new \stdClass();
        $menuItem->requirement = 0;
        $menuItem->target = 'rep';
        $menuItem->menu_id = 'rep0';
        $menuItem->label = xlt("Prescription Log");
        $menuItem->url = self::MODULE_INSTALLATION_PATH . "/templates/rxlogmanager.php";
        $menuItem->children = [];
        $menuItem->acl_req = ["patients", "rx"];
        $menuItem->global_req = ["weno_rx_enable"];

        //Weno Management
        $mgtMenu = new \stdClass();
        $mgtMenu->requirement = 0;
        $mgtMenu->target = 'adm0';
        $mgtMenu->menu_id = 'adm';
        $mgtMenu->label = xlt("Weno Management");
        $mgtMenu->url = self::MODULE_INSTALLATION_PATH . "/templates/facilities.php";
        $mgtMenu->children = [];
        $mgtMenu->acl_req = ["admin", "super"];
        $mgtMenu->global_req = ["weno_rx_enable"];

        foreach ($menu as $item) {
            if ($item->menu_id == 'admimg') {
                foreach ($item->children as $other) {
                    if ($other->label == 'Other') {
                        $other->children[] = $mgtMenu;
                        break;
                    }
                }
            }

            if ($item->menu_id == 'repimg') {
                foreach ($item->children as $clientReport) {
                    if ($clientReport->label == 'Clients') {
                        $clientReport->children[] = $menuItem;
                        break;
                    }
                }
            }
        }

        $event->setMenu($menu);

        return $event;
    }

    public function demographicsSelectorEvents()
    {
        $this->eventDispatcher->addListener(RenderPharmacySectionEvent::RENDER_AFTER_PHARMACY_SECTION, [$this, 'renderWenoPharmacySelector']);
    }

    public function renderWenoPharmacySelector()
    {
        include_once($this->modulePath) . "/templates/pharmacy_list_form.php";
    }

    public function demographicsDisplaySelectedEvents()
    {
        $this->eventDispatcher->addListener(RenderPharmacySectionEvent::RENDER_AFTER_SELECTED_PHARMACY_SECTION, [$this, 'renderSelectedWenoPharmacies']);
    }

    public function renderSelectedWenoPharmacies()
    {
        echo "<br>";
        include_once($this->modulePath) . "/templates/pharmacy_list_display.php";
    }

    public function patientSaveEvents()
    {
        $this->eventDispatcher->addListener(PatientBeforeCreatedAuxEvent::EVENT_HANDLE, [$this, 'persistPatientWenoPharmacies']);
    }

    public function persistPatientWenoPharmacies(PatientBeforeCreatedAuxEvent $event)
    {
        $patientData = $event->getPatientData();
        $this->selectedPatientPharmacy->prepSelectedPharmacy($patientData);
    }

    public function patientUpdateEvents()
    {
        $this->eventDispatcher->addListener(PatientUpdatedEventAux::EVENT_HANDLE, [$this, 'updatePatientWenoPharmacies']);
    }

    public function updatePatientWenoPharmacies(PatientUpdatedEventAux $event)
    {
        $updatedPatientData = $event->getUpdatedPatientData();
        $this->selectedPatientPharmacy->prepForUpdatePharmacy($updatedPatientData);
    }
}

?>
