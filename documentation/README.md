# Comprendre le fonctionnement de CAS

VitiConnect utilise la technologie [Apereo/CAS](https://www.apereo.org/projects/cas).

Dans cette documentation, nous expliquons le fonctionnement de ce standard ouvert. Le CAS est accessible depuis la base d'url **https://viticonnect.net/cas/**. Ainsi l'url de login générique est [https://viticonnect.net/cas/login](https://viticonnect.net/cas/login).

Imaginons que l'application cherchant à savoir si un utilisateur est identifié est **site.example.org**. Pour s'authentifier l'utilisateur utilise *http://site.example.org/connexion/*.

CAS nécessite deux type d'interrogation. Des interrogations réalisée via des requêtes du navigateur de l'utilisateur final (*Etape navigateur* dans la suite de la documentation), d'autres qui sont réalisé via un appel API entre l'application finale (site.example.org dans notre exemple) et le CAS (*Etape API* dans la suite de la documentation).

Une documentation concernant l'ajout d'une instance est disponible [ici](https://github.com/24eme/cas-template-factory/blob/master/README.md#dockerization).

## L'application redirige l'utilisateur vers le CAS

**Etape navigateur**

Pour que l'application sache si l'utilisateur est identifié il faut qu'elle le redirige (HTTP 302) vers l'url suivante :

    https://viticonnect.net/cas/login?service=http://site.example.org/connexion/

L'utilisateur est alors invité à choisir son site d'authentification puis à saisir son login et son mot de passe.

## Authentifié, l'utilisateur est redirigé vers l'application

**Etape navigateur**

Un fois authentifié, l'application CAS redirigera l'utilisateur vers la page spécifiée dans l'argument service en lui ajoutant un argument ticket :

    http://site.example.org/connexion?ticket=ST-Y-XXXXXXXXXXXXXXXX-cas

## L'application vérifie le ticket via l'API CAS

**Etape API/CAS**

Pour savoir si le ticket est valable, l'application doit maintenant elle même interroger le serveur CAS pour lui demander si le ticket et valable, via l'API `/cas/serviceValidate`.

Cette API attend deux paramètres :

 - `ticket` : qui contient le ticket qui a été fourni par le navigateur de l'utilisateur
 - `service` : qui contient la même chaine de caractère que lors de l'appel `/cas/login` depuis le navigateur de l'utilisateur.

Voici l'url à construire :

    https://viticonnect.net/cas/serviceValidate?ticket=ST-Y-XXXXXXXXXXXXXXXX-cas&service=http://site.example.org/connexion/

Attention, l'argument `service` doit être strictement le même que celui fourni par l'utilisateur lors du login.

Un XML est retourné avec la raison de l'erreur si le ticket est erroné ou des infos concernant l'utilisateur :

    <cas:serviceResponse xmlns:cas='http://www.yale.edu/tp/cas'>
         <cas:authenticationSuccess>
              <cas:user>login</cas:user>
              <cas:attributes>
                  <cas:email>email@example.org</cas:email>
                  <cas:nom>Nom Utilisateur</cas:nom>
              </cas:attributes>
         </cas:authenticationSuccess>
    </cas:serviceResponse>

Viticonnect rajoute des champs dédiés aux identifiants nationaux viticoles : CVI, SIRET, Accises, ...

Voici un exemple de retour :

    <cas:serviceResponse>
      <cas:authenticationSuccess>
        <cas:user>6899900999</cas:user>
        <cas:attributes>
          <cas:nom>Compte de Test</cas:nom>
          <cas:email>test@example.org</cas:email>
          <cas:viticonnect_entities_number>1</cas:viticonnect_entities_number>
          <cas:viticonnect_entity_1_raison_sociale>Chais de Test 1</cas:viticonnect_entity_1_raison_sociale>
          <cas:viticonnect_entity_1_cvi>6899900999</cas:viticonnect_entity_1_cvi>
          <cas:viticonnect_entity_1_accises>FR00000000000</cas:viticonnect_entity_1_accises>
          <cas:viticonnect_entities_all_raison_sociale>Chais de Test 1</cas:viticonnect_entities_all_raison_sociale>
          <cas:viticonnect_entities_all_cvi>6899900999</cas:viticonnect_entities_all_cvi>
          <cas:viticonnect_entities_all_accises>FR00000000000</cas:viticonnect_entities_all_accises>
        </cas:attributes>
      </cas:authenticationSuccess>
    </cas:serviceResponse>

Il se peut que le compte soit lié à plusieurs chais d'une exploitation viticole. C'est pour cette raison que l'API peut retourner plusieurs éléments *entity*.

## Restreindre l'usage de Viticonnect à un ou plusieurs acteurs

Si vous souhaitez utiliser Viticonnect aux utilisateurs d'un seul acteur (une seule interpro, une seule ODG), vous pouvez ajouter dans l'url de connexion que vous fournissez à vos utilisateurs l'acteur concerné entre les chaines "cas" et "login" : https://viticonnect.net/cas/ACTEUR/login?service=https://localhost/.

Ainsi pour limiter l'usage de viticonnect au CIVA, vous pouvez rediriger vos utilisateur vers https://viticonnect.net/cas/civa/login?service=https://localhost

Si vous souhaitez sélectionner plusieurs acteurs, vous pouvez les séparer par des virgules : https://viticonnect.net/cas/civa,declarvins/login?service=https://localhost

Les identifiant des acteurs possibles sont disponibles depuis le fichier [config/services.config.php](../config/services.config.php).
