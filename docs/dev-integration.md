# Integration locale en mode developpement

Ce guide permet d'integrer le bundle dans un projet Symfony existant situe
dans un autre repertoire, sans publier de version sur Packagist.

## 1. Utiliser le bundle depuis un projet voisin

Exemple d'arborescence :

~~~
~/PhpstormProjects/
├── monitoring-bundle/
└── my-application/
~~~

Depuis my-application, ajouter le depot local dans composer.json :

~~~
{
    "repositories": [
        {
            "type": "path",
            "url": "../monitoring-bundle",
            "options": {
                "symlink": true
            }
        }
    ]
}
~~~

Puis installer le bundle :

~~~
composer require ad2210/monitoring-bundle:@dev
~~~

Le lien symbolique permet de modifier le bundle et de tester immediatement
les changements dans l'application. Pour simuler une installation distante,
remplacer le depot path par le depot Git du bundle.

## 2. Enregistrer le bundle

Dans config/bundles.php :

~~~
return [
    // ...
    Ad2210\MonitoringBundle\Ad2210MonitoringBundle::class => ['all' => true],
];
~~~

Le bundle peut etre charge dans tous les environnements. Pour le desactiver
dans un environnement, utiliser enabled: false dans sa configuration.

## 3. Importer les routes

Creer config/routes/monitoring.yaml :

~~~
monitoring_bundle:
    resource: '@Ad2210MonitoringBundle/Resources/config/routes.php'
~~~

Verifier les routes :

~~~
php bin/console debug:router | grep monitoring
~~~

Les routes exposees sont :

~~~
/_monitoring/health/live
/_monitoring/health/ready
/_monitoring/metrics
~~~

## 4. Configurer l'application en dev

Creer config/packages/ad2210_monitoring.yaml :

~~~
ad2210_monitoring:
    enabled: true
    application_name: '%env(MONITORING_APPLICATION_NAME)%'
    environment: '%kernel.environment%'
    version: '%env(default:dev:MONITORING_APPLICATION_VERSION)%'
    health:
        enabled: true
        token: '%env(MONITORING_HEALTH_TOKEN)%'
    metrics:
        enabled: true
        token: '%env(MONITORING_METRICS_TOKEN)%'
~~~

Ajouter les variables dans .env.local :

~~~
MONITORING_APPLICATION_NAME=demo-app
MONITORING_APPLICATION_VERSION=dev
MONITORING_HEALTH_TOKEN=local-health-token
MONITORING_METRICS_TOKEN=local-metrics-token
~~~

Le token health protege live et ready. Le token metrics protege metrics
independamment. Pour un test strictement local, les tokens peuvent etre
vides ; sur un reseau partage, utiliser des tokens.

## 5. Verifier les endpoints

Depuis le projet applicatif :

~~~
php bin/console cache:clear
php bin/console debug:router | grep ad2210_monitoring
symfony server:start -d
curl -H 'X-Monitoring-Token: local-health-token' \
    http://127.0.0.1:8000/_monitoring/health/live
curl -H 'X-Monitoring-Token: local-health-token' \
    http://127.0.0.1:8000/_monitoring/health/ready
curl -H 'X-Monitoring-Token: local-metrics-token' \
    http://127.0.0.1:8000/_monitoring/metrics
~~~

Resultats attendus :

- live retourne HTTP 200 si le bundle est active et le kernel repond ;
- ready retourne HTTP 200 si toutes les readiness checks reussissent,
  sinon HTTP 503 ;
- metrics retourne du texte au format Prometheus, notamment
  ad2210_monitoring_up.

Un 401 indique un token absent ou incorrect. Un 404 indique que le bundle est
desactive ou que ses routes ne sont pas importees.

## 6. Mercure, Messenger et Scheduler

Le bundle ne detecte pas automatiquement ces composants. Il fournit toutefois
le point d'extension ReadinessCheckInterface.

Exemple de check :

~~~
namespace App\Monitoring;

use Ad2210\MonitoringBundle\Health\ReadinessCheckInterface;
use Ad2210\MonitoringBundle\Health\ReadinessCheckResult;

final class MessengerReadinessCheck implements ReadinessCheckInterface
{
    public function getName(): string
    {
        return 'messenger';
    }

    public function check(): ReadinessCheckResult
    {
        // Remplacer par une verification propre au projet.
        return new ReadinessCheckResult('ok');
    }
}
~~~

Enregistrer le check dans config/services.yaml :

~~~
services:
    App\Monitoring\MessengerReadinessCheck:
        tags:
            - { name: ad2210_monitoring.readiness_check }
~~~

Repeter le meme principe pour Mercure et Scheduler. Un check doit rester
rapide, ne pas lancer de commande longue et retourner unhealthy avec un
message non sensible lorsqu'une dependance est indisponible.

Le bundle ne fournit pas encore de metriques automatiques pour la taille des
queues Messenger, l'etat des workers, Mercure ou Scheduler. Ces metriques
doivent etre exposees par le projet applicatif ou un exporter dedie.

La commande heartbeat est disponible pour un worker, cron ou timer :

~~~
php bin/console monitoring:heartbeat
~~~

Elle ecrit un JSON sur stdout ; son transport vers Monitoring Center reste a
configurer dans le projet.

## 7. Prometheus local

Ajouter une cible dans Prometheus :

~~~
scrape_configs:
    - job_name: demo-app
      metrics_path: /_monitoring/metrics
      static_configs:
          - targets: ['host.docker.internal:8000']
      authorization:
          credentials: local-metrics-token
~~~

Si l'application tourne dans Docker, remplacer la cible par le nom du
service et son port interne. Tester la configuration avec :

~~~
promtool check config /etc/prometheus/prometheus.yml
~~~

## 8. Tests du projet integrateur

Apres installation :

~~~
composer dump-autoload
php bin/console lint:container
php bin/console debug:router | grep monitoring
curl -i http://127.0.0.1:8000/_monitoring/health/live
~~~

Pour tester le bundle lui-meme depuis son depot :

~~~
cd ../monitoring-bundle
composer qa
~~~

Avant une release, remplacer le depot path par une reference Git ou une
version publiee afin de verifier que l'integration ne depend pas du lien
symbolique local.
