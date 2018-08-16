<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'anix');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'Chyse');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', '');

/** Nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Charset do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ',dSmu YXn+5NjQlhWGmv{sT5bQo!J3q2IHbp`zl_n8bM83pfBxY/P=T[IT,#?+qL');
define('SECURE_AUTH_KEY',  '*<|bYZDceJX}TM^=>Hg+e$ctXQ2`da|^zvz/t=>w7USI:?=KF9#33,C~du ?:O ?');
define('LOGGED_IN_KEY',    'gTZ ,X4; wX}/hPk`]tHp_a:_1s&U9E A.l9h$;w@TV0J(v%RrmpK2$2ti>R-DO%');
define('NONCE_KEY',        'O=ZZ.?OeQA0Za`f{=uIWF_4n_[(kCgWPx7S}X<`7vuTe2*h^#jr)0grE%ad.B+xs');
define('AUTH_SALT',        'k]~B6970M^OFSS7E_>j$1.UbE/xqg2bQmdQnN-4r.7W+{h3+#L}~7n&pVJ,@5<wW');
define('SECURE_AUTH_SALT', 'BafyMer,m{Dw<NF@1_j2vxh7-LK`JscS+-YN&Mrr&T!Nl:[=X`B?DALKY1]a}x>M');
define('LOGGED_IN_SALT',   '4jJ_U+-y%B4~W:/?AC);lbwDDf26W8|YbWFyD>x;L:%Kf`WV@mtyA@Cfvgjn$.;A');
define('NONCE_SALT',       'LNkpRykD0V,7mFvp>P*[9[<mG${1bHi._0!G>*XD:vHW!ZK8FyJnq)p5!=ND![SY');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
