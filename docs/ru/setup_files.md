# Базовые конфигурационные скрипты Limb3 приложений
За время практики разработки приложений, использующих Limb3, была выработана следующая схема базовой конфигурации приложения.

* В директории приложения создается скрипт **setup.php**, в котором происходят подключения библиотек, объявление констант, настройка include_path по умолчанию и проч. Например:

        <?php
        set_include_path(dirname(__FILE__) . PATH_SEPARATOR .           //поддержка удобного подключения проектных скриптов
                         dirname(__FILE__) . '/lib/' . PATH_SEPARATOR . //подразумевается, что Limb3 поставляется в директории lib проекта
                         get_include_path());
 
        //setup.override.php подключается *после* установки include_path, но *до* определения констант
        if(file_exists(dirname(__FILE__) . '/setup.override.php'))
          require_once(dirname(__FILE__) . '/setup.override.php');
 
        @define('LIMB_VAR_DIR', dirname(__FILE__) . '/var');
 
        require_once('limb/core/common.inc.php');
        ?>

* Как видно из кода выше, в setup.php опционально подключается скрипт **setup.override.php**, если таковой существует. setup.override.php служит для того, чтобы во время разработки у разработчика была возможность переопределить конфигурационные значения setup.php или установить новые. Например, include_path, чтобы использовать devel версии пакетов Limb3 вместо поставляемых вместе с приложением в директории lib или установка иных значений констант, которые имеют смысл только на время разработки. Пример подобного setup.override.php:

        <?php
        set_time_limit(0);
        restore_include_path();
        set_include_path(dirname(__FILE__) . '/' . PATH_SEPARATOR .
                         '/home/bob/var/dev/limb/3.x/' . PATH_SEPARATOR);
 
        @define('USE_PROXY', '192.168.0.20:3128');
        @define('LIMB_VAR_DIR', '/tmp/var'); 
        ?>

Важно понимать, что setup.override.php должен игнорироваться системой версионного контроля (svn:ignore), дабы частные установки разработчиков случайным образом не проскользнули в репозиторий.

* HTTP шлюз (например, www/index.php), через который происходит отработка всех запросов, подключает setup.php:

        <?php
        require_once(dirname(__FILE__) . '/../setup.php');
        require_once('src/LimbProjectApplication.class.php');
 
        $application = new LimbProjectApplication();
        $application->process();
        ?> 

Также на тему базовых настроек Limb3 приложений рекомендуем ознакомиться с [правилами](./constants.md) использования конфигурационных констант в Limb3.
