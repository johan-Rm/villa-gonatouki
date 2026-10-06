#!/bin/bash
# Script de synchronisation adapté pour Docker
# Date: $(date +%Y-%m-%d.%Hh%M)
#
# Utilisation:
# # Synchroniser tout (ressources + base de données)
# ./site-sync.sh
#
# # Synchroniser uniquement les ressources
# ./site-sync.sh -r
#
# # Importer uniquement la base de données
# ./site-sync.sh -d
#
# # Afficher l'aide
# ./site-sync.sh -h

############################
# Variables de connexion SSH #
############################
host=${SYNC_HOST:-}
port=22
user=${SYNC_USER:-}

#################################
# Variables de connexion BDD #
#################################
# Production
db_prod_host=${DB_PROD_HOST:-}
db_prod_name=${DB_PROD_NAME:-}
db_prod_user=${DB_PROD_USER:-}
# Utiliser la variable d'environnement ou une valeur par défaut
db_prod_pass=${DB_PROD_PASSWORD:-}

# Docker (local)
db_local_name=dms_villa_gonatouki
db_local_user=root
# Utiliser la variable d'environnement ou une valeur par défaut
db_local_pass=${DB_DOCKER_PASSWORD:-}

##########################
# Variables des chemins #
##########################
# Chemins sur le serveur de production
directory_prod_backend=/var/www/villa-gonatouki/digital-management-system
directory_prod_frontend=/var/www/villa-gonatouki/nuxt-modern-website

# Chemins locaux
directory_local_backend=$(cd "$(dirname "$0")" && pwd)/digital-management-system
directory_local_frontend=$(cd "$(dirname "$0")" && pwd)/nuxt-modern-website

###########################################
# Configuration et variables               #
###########################################

# Couleurs pour les logs
RESET="\033[0m"
BLEU="\033[0;34m"
VERT="\033[0;32m"
JAUNE="\033[0;33m"
ROUGE="\033[0;31m"

###########################################
# Fonctions de base                        #
###########################################

# Fonctions de logging (définies en premier pour être utilisables partout)
log_info() {
    local message="$1"
    local timestamp=$(date "+%Y-%m-%d %H:%M:%S")
    echo -e "${BLEU}[INFO]${RESET} $message"
}

log_success() {
    local message="$1"
    local timestamp=$(date "+%Y-%m-%d %H:%M:%S")
    echo -e "${VERT}[SUCCÈS]${RESET} $message"
}

log_warning() {
    local message="$1"
    local timestamp=$(date "+%Y-%m-%d %H:%M:%S")
    echo -e "${JAUNE}[ATTENTION]${RESET} $message"
}

log_error() {
    local message="$1"
    local timestamp=$(date "+%Y-%m-%d %H:%M:%S")
    echo -e "${ROUGE}[ERREUR]${RESET} $message"
    exit 1
}

###########################################
# Fonctions                               #
###########################################

# Vérification des conteneurs Docker
check_docker_containers() {
    log_info "Vérification des conteneurs Docker..."
    
    # Vérifier si docker-compose est disponible
    if ! command -v docker-compose &> /dev/null; then
        log_error "docker-compose n'est pas installé"
    fi
    
    # Vérifier si les conteneurs sont en cours d'exécution
    local containers_running=$(docker-compose ps --services --filter "status=running" | grep -c "db")
    
    if [ "$containers_running" -eq 0 ]; then
        log_warning "Les conteneurs Docker ne sont pas en cours d'exécution"
        log_info "Démarrage des conteneurs Docker..."
        
        # Vérifier si les images existent déjà
        local images_exist=$(docker-compose images -q | wc -l)
        
        if [ "$images_exist" -eq 0 ]; then
            log_info "Construction des images Docker..."
            docker-compose build
            
            if [ $? -ne 0 ]; then
                log_error "Erreur lors de la construction des images Docker"
            fi
        fi
        
        # Démarrer les conteneurs
        docker-compose up -d
        
        if [ $? -ne 0 ]; then
            log_error "Erreur lors du démarrage des conteneurs Docker"
        fi
        
        # Attendre que MySQL soit prêt
        log_info "Attente du démarrage de MySQL..."
        sleep 10
    else
        log_success "Les conteneurs Docker sont en cours d'exécution"
    fi
}

# Fonction pour vérifier les dossiers
check_directories() {
    log_info "Vérification des dossiers de destination..."
    
    if [ ! -d "$directory_local_backend/public" ]; then
        mkdir -p "$directory_local_backend/public"
        log_info "Dossier $directory_local_backend/public créé"
    fi

    if [ ! -d "$directory_local_frontend/static" ]; then
        mkdir -p "$directory_local_frontend/static"
        log_info "Dossier $directory_local_frontend/static créé"
    fi
}

# Fonction pour synchroniser les ressources
sync_resources() {
    log_info "Synchronisation des ressources backend..."
    rsync -azvc --stats --delete --force --omit-dir-times --no-perms -e "ssh -p $port" \
        $user@$host:"$directory_prod_backend/public/media/" \
        $directory_local_backend/public/media/
    
    if [ $? -ne 0 ]; then
        log_error "Erreur lors de la synchronisation des images backend"
    fi

    log_info "Synchronisation des ressources frontend..."
    rsync -azvc --stats --delete --force --omit-dir-times --no-perms -e "ssh -p $port" \
        $user@$host:"$directory_prod_frontend/static/" \
        $directory_local_frontend/static/
    
    if [ $? -ne 0 ]; then
        log_error "Erreur lors de la synchronisation des ressources frontend"
    fi

    log_success "Synchronisation des ressources terminée"
}

# Fonction pour importer la base de données
import_database() {
    log_info "Création du dump de la base de données distante..."
    ssh $user@$host -p $port "export MYSQL_PWD='$db_prod_pass'; cd $directory_prod_backend && mysqldump --single-transaction --host=$db_prod_host \
        --user=$db_prod_user --port=3306 \
        $db_prod_name > mysqldump.sql --no-tablespaces \
        && zip -o mysqldump.zip mysqldump.sql"
    
    if [ $? -ne 0 ]; then
        log_error "Erreur lors de la création du dump distant"
    fi

    log_info "Téléchargement du dump..."
    scp -r $user@$host:$directory_prod_backend/mysqldump.zip $directory_local_backend/mysqldump.zip
    
    if [ $? -ne 0 ]; then
        log_error "Erreur lors du téléchargement du dump"
    fi

    log_info "Décompression du dump..."
    unzip -o $directory_local_backend/mysqldump.zip -d $directory_local_backend
    
    if [ $? -ne 0 ]; then
        log_error "Erreur lors de la décompression du dump"
    fi
    
    # Suppression du fichier zip après décompression
    log_info "Suppression du fichier zip..."
    rm -f $directory_local_backend/mysqldump.zip

    log_info "Importation dans la base Docker..."
    
    # Création de la base si elle n'existe pas
    docker-compose exec db mysql -u$db_local_user -p"$db_local_pass" \
        -e "CREATE DATABASE IF NOT EXISTS $db_local_name DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    
    if [ $? -ne 0 ]; then
        log_error "Erreur lors de la création de la base de données"
    fi

    # Vérification de l'existence du fichier SQL
    if [ ! -f "$directory_local_backend/mysqldump.sql" ]; then
        log_error "Le fichier SQL est introuvable : $directory_local_backend/mysqldump.sql"
    fi
    
    # Import du dump dans Docker
    docker-compose exec -T db mysql -u$db_local_user -p"$db_local_pass" $db_local_name < $directory_local_backend/mysqldump.sql
    
    if [ $? -ne 0 ]; then
        log_error "Erreur lors de l'importation du dump dans Docker"
    fi

    log_success "Importation de la base de données terminée"
}

# Fonction pour afficher l'aide
show_help() {
    echo "Usage: $0 [options]"
    echo ""
    echo "Options:"
    echo "  -h, --help          Affiche cette aide"
    echo "  -r, --resources     Synchronise uniquement les ressources"
    echo "  -d, --database      Importe uniquement la base de données"
    echo "  -a, --all           Synchronise les ressources et importe la base (défaut)"
    echo ""
    exit 0
}

###########################################
# Programme principal                     #
###########################################

# Charger les variables d'environnement depuis .env si le fichier existe
ENV_FILE="$(dirname "$0")/.env"
if [ -f "$ENV_FILE" ]; then
    source "$ENV_FILE"
    log_info "Variables d'environnement chargées avec succès"
fi


# Traitement des arguments
SYNC_RESOURCES=true
IMPORT_DATABASE=true

while [[ $# -gt 0 ]]; do
    key="$1"
    case $key in
        -h|--help)
            show_help
            ;;
        -r|--resources)
            SYNC_RESOURCES=true
            IMPORT_DATABASE=false
            shift
            ;;
        -d|--database)
            SYNC_RESOURCES=false
            IMPORT_DATABASE=true
            shift
            ;;
        -a|--all)
            SYNC_RESOURCES=true
            IMPORT_DATABASE=true
            shift
            ;;
        *)
            log_error "Option inconnue: $1"
            show_help
            ;;
    esac
done

host=${SYNC_HOST:-$host}
user=${SYNC_USER:-$user}
db_prod_host=${DB_PROD_HOST:-$db_prod_host}
db_prod_name=${DB_PROD_NAME:-$db_prod_name}
db_prod_user=${DB_PROD_USER:-$db_prod_user}
db_prod_pass=${DB_PROD_PASSWORD:-$db_prod_pass}
db_local_pass=${DB_DOCKER_PASSWORD:-$db_local_pass}
: "${host:?Configure SYNC_HOST}" "${user:?Configure SYNC_USER}"
if [ "$IMPORT_DATABASE" = true ]; then
    : "${db_prod_host:?Configure DB_PROD_HOST}" "${db_prod_name:?Configure DB_PROD_NAME}"
    : "${db_prod_user:?Configure DB_PROD_USER}" "${db_prod_pass:?Configure DB_PROD_PASSWORD}"
    : "${db_local_pass:?Configure DB_DOCKER_PASSWORD}"
fi

# Exécution des fonctions
echo -e "\n${BLEU}=== SYNCHRONISATION VILLA GONATOUKI ===${RESET}"
echo -e "${BLEU}=== $(date "+%Y-%m-%d %H:%M:%S") ===${RESET}\n"

# Vérification des conteneurs Docker
check_docker_containers

# Vérification des dossiers
check_directories

# Synchronisation des ressources
if [ "$SYNC_RESOURCES" = true ]; then
    sync_resources
fi

# Importation de la base de données
if [ "$IMPORT_DATABASE" = true ]; then
    import_database
fi

# Fin du script
log_success "Synchronisation complète terminée avec succès!"