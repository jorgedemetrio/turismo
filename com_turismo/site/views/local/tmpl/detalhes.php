<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

// Carrega Bootstrap e jQuery
HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('jquery.framework');

// Carrega os assets do componente
$wa = $this->document->getWebAssetManager();
$wa->useScript('com_turismo.site')
   ->useStyle('com_turismo.site');

// Carrega Google Maps API
$apiKey = $this->params->get('google_maps_key', '');
if ($apiKey) {
    $this->document->addScript("https://maps.googleapis.com/maps/api/js?key={$apiKey}", [], ['defer' => true]);
}

// Verifica se o usuário está logado
$user = Factory::getUser();
$isLoggedIn = !$user->guest;

// Prepara o endereço completo para o mapa
$endereco = implode(', ', array_filter([
    $this->item->endereco . ', ' . $this->item->numero,
    $this->item->complemento,
    $this->item->bairro,
    $this->item->cidade,
    $this->item->uf,
    $this->item->cep
]));

// Adiciona os metadados do Google Data Search
$schema = [
    '@context' => 'https://schema.org',
    '@type' => $this->item->google_type,
    'name' => $this->item->nome,
    'description' => $this->item->descricao,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $this->item->endereco . ', ' . $this->item->numero,
        'addressLocality' => $this->item->cidade,
        'addressRegion' => $this->item->uf,
        'postalCode' => $this->item->cep,
        'addressCountry' => 'Brasil'
    ]
];

// Adiciona coordenadas se disponíveis
if ($this->item->latitude && $this->item->longitude) {
    $schema['geo'] = [
        '@type' => 'GeoCoordinates',
        'latitude' => $this->item->latitude,
        'longitude' => $this->item->longitude
    ];
}

// Adiciona tipo de culinária se disponível
if ($this->item->tipo_culinaria) {
    $schema['servesCuisine'] = $this->item->tipo_culinaria;
}

// Adiciona avaliações se disponíveis
if ($this->item->total_avaliacoes > 0) {
    $schema['aggregateRating'] = [
        '@type' => 'AggregateRating',
        'ratingValue' => $this->item->avaliacao_media,
        'reviewCount' => $this->item->total_avaliacoes
    ];
}

// Adiciona fotos se disponíveis
if (!empty($this->fotos)) {
    $schema['image'] = array_map(function($foto) {
        return Uri::root() . $foto->arquivo;
    }, $this->fotos);
}

// Adiciona cardápio se disponível
if (!empty($this->cardapio)) {
    $schema['hasMenu'] = [
        '@type' => 'Menu',
        'hasMenuSection' => array_map(function($categoria) {
            return [
                '@type' => 'MenuSection',
                'name' => $categoria->nome,
                'hasMenuItem' => array_map(function($item) {
                    return [
                        '@type' => 'MenuItem',
                        'name' => $item->nome,
                        'description' => $item->descricao,
                        'price' => $item->preco
                    ];
                }, $categoria->itens)
            ];
        }, $this->cardapio)
    ];
}

// Adiciona o script JSON-LD ao documento
$this->document->addCustomTag('<script type="application/ld+json">' . json_encode($schema) . '</script>');
?>

<div class="com-turismo-local" itemscope itemtype="https://schema.org/<?php echo $this->escape($this->item->google_type); ?>">
    <h1 itemprop="name"><?php echo $this->escape($this->item->tipo_nome) . ' : ' . $this->escape($this->item->nome); ?></h1>

    <?php if (!empty($this->fotos)) : ?>
    <!-- Galeria de Fotos -->
    <div class="turismo-galeria mb-4">
        <div id="carouselFotos" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($this->fotos as $i => $foto) : ?>
                    <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo Uri::root() . $foto->arquivo; ?>" 
                             class="d-block w-100" 
                             alt="<?php echo $this->escape($foto->titulo); ?>"
                             itemprop="image">
                        <?php if ($foto->titulo || $foto->descricao) : ?>
                            <div class="carousel-caption d-none d-md-block">
                                <?php if ($foto->titulo) : ?>
                                    <h5><?php echo $this->escape($foto->titulo); ?></h5>
                                <?php endif; ?>
                                <?php if ($foto->descricao) : ?>
                                    <p><?php echo $this->escape($foto->descricao); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($this->fotos) > 1) : ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselFotos" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"><?php echo Text::_('JPREV'); ?></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselFotos" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"><?php echo Text::_('JNEXT'); ?></span>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <!-- Avaliação média -->
            <?php if ($this->item->total_avaliacoes > 0) : ?>
                <div class="turismo-rating mb-3" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                    <span class="me-2">
                        <?php echo Text::_('COM_TURISMO_AVALIACAO_MEDIA'); ?>: 
                        <span itemprop="ratingValue"><?php echo number_format($this->item->avaliacao_media, 1); ?></span>
                        (<span itemprop="reviewCount"><?php echo $this->item->total_avaliacoes; ?></span> <?php echo Text::_('COM_TURISMO_AVALIACOES'); ?>)
                    </span>
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <i class="fas fa-star <?php echo ($i <= $this->item->avaliacao_media) ? 'text-warning' : 'text-muted'; ?>"></i>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

            <!-- Informações do local -->
            <div class="card mb-3">
                <div class="card-body">
                    <?php if ($this->item->cnpj) : ?>
                        <p><strong><?php echo Text::_('COM_TURISMO_CNPJ'); ?>:</strong> 
                           <span itemprop="taxID"><?php echo $this->escape($this->item->cnpj); ?></span>
                        </p>
                    <?php endif; ?>

                    <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                        <p><strong><?php echo Text::_('COM_TURISMO_ENDERECO'); ?>:</strong><br>
                           <span itemprop="streetAddress"><?php echo $this->escape($endereco); ?></span>
                        </p>
                    </div>

                    <?php if ($this->item->valor_medio) : ?>
                        <p><strong><?php echo Text::_('COM_TURISMO_VALOR_MEDIO'); ?>:</strong> 
                           R$ <?php echo number_format($this->item->valor_medio, 2, ',', '.'); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($this->item->tipo_culinaria) : ?>
                        <p><strong><?php echo Text::_('COM_TURISMO_TIPO_CULINARIA'); ?>:</strong>
                           <span itemprop="servesCuisine"><?php echo $this->escape($this->item->tipo_culinaria); ?></span>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Mapa -->
            <div id="turismo-map" class="turismo-map" 
                 data-lat="<?php echo $this->item->latitude; ?>" 
                 data-lng="<?php echo $this->item->longitude; ?>"
                 data-title="<?php echo $this->escape($this->item->nome); ?>"
                 data-address="<?php echo $this->escape($endereco); ?>">
            </div>
        </div>
    </div>

    <?php if (!empty($this->cardapio)) : ?>
    <!-- Cardápio -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><?php echo Text::_('COM_TURISMO_CARDAPIO'); ?></h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div itemprop="hasMenu" itemscope itemtype="https://schema.org/Menu">
                    <?php foreach ($this->cardapio as $categoria) : ?>
                        <div itemprop="hasMenuSection" itemscope itemtype="https://schema.org/MenuSection">
                            <h4 itemprop="name"><?php echo $this->escape($categoria->nome); ?></h4>
                            <div class="row">
                                <?php foreach ($categoria->itens as $item) : ?>
                                    <div class="col-md-6 mb-3" itemprop="hasMenuItem" itemscope itemtype="https://schema.org/MenuItem">
                                        <div class="card h-100">
                                            <?php if ($item->foto) : ?>
                                                <img src="<?php echo Uri::root() . $item->foto; ?>" 
                                                     class="card-img-top" 
                                                     alt="<?php echo $this->escape($item->nome); ?>"
                                                     itemprop="image">
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h5 class="card-title" itemprop="name"><?php echo $this->escape($item->nome); ?></h5>
                                                <p class="card-text" itemprop="description"><?php echo $this->escape($item->descricao); ?></p>
                                                <p class="card-text">
                                                    <strong itemprop="price">R$ <?php echo number_format($item->preco, 2, ',', '.'); ?></strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Descrição -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><?php echo Text::_('COM_TURISMO_DESCRICAO'); ?></h3>
        </div>
        <div class="card-body">
            <div itemprop="description">
                <?php echo $this->item->descricao; ?>
            </div>
        </div>
    </div>

    <!-- Formulário de Contato -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><?php echo Text::_('COM_TURISMO_ENVIAR_MENSAGEM'); ?></h3>
        </div>
        <div class="card-body">
            <form action="<?php echo Route::_('index.php?option=com_turismo&task=local.enviarMensagem'); ?>" 
                  method="post" 
                  class="form-validate">
                
                <div class="mb-3">
                    <label for="nome" class="form-label required"><?php echo Text::_('COM_TURISMO_NOME'); ?></label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label required"><?php echo Text::_('COM_TURISMO_EMAIL'); ?></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="telefone" class="form-label"><?php echo Text::_('COM_TURISMO_TELEFONE'); ?></label>
                    <input type="tel" class="form-control telefone" id="telefone" name="telefone">
                </div>
                
                <div class="mb-3">
                    <label for="mensagem" class="form-label required"><?php echo Text::_('COM_TURISMO_MENSAGEM'); ?></label>
                    <textarea class="form-control" id="mensagem" name="mensagem" rows="4" required></textarea>
                </div>

                <!-- Captcha -->
                <?php echo $this->form->renderField('captcha'); ?>

                <?php echo HTMLHelper::_('form.token'); ?>
                <input type="hidden" name="id" value="<?php echo $this->item->id; ?>">
                <button type="submit" class="btn btn-primary"><?php echo Text::_('COM_TURISMO_ENVIAR'); ?></button>
            </form>
        </div>
    </div>

    <!-- Avaliações -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><?php echo Text::_('COM_TURISMO_AVALIACOES'); ?></h3>
        </div>
        <div class="card-body">
            <?php if ($isLoggedIn) : ?>
                <!-- Formulário de Avaliação -->
                <form action="<?php echo Route::_('index.php?option=com_turismo&task=local.avaliar'); ?>" 
                      method="post" 
                      class="form-validate mb-4">
                    
                    <div class="mb-3">
                        <label class="form-label required"><?php echo Text::_('COM_TURISMO_SUA_AVALIACAO'); ?></label>
                        <div class="turismo-rating-input">
                            <?php for ($i = 5; $i >= 1; $i--) : ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required />
                                <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comentario" class="form-label"><?php echo Text::_('COM_TURISMO_COMENTARIO'); ?></label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3"></textarea>
                    </div>

                    <?php echo HTMLHelper::_('form.token'); ?>
                    <input type="hidden" name="id" value="<?php echo $this->item->id; ?>">
                    <button type="submit" class="btn btn-primary"><?php echo Text::_('COM_TURISMO_AVALIAR'); ?></button>
                </form>
            <?php else : ?>
                <div class="alert alert-info">
                    <?php echo Text::_('COM_TURISMO_LOGIN_PARA_AVALIAR'); ?>
                    <a href="<?php echo Route::_('index.php?option=com_users&view=login&return=' . base64_encode(Uri::getInstance()->toString())); ?>" 
                       class="btn btn-primary ms-2">
                        <?php echo Text::_('COM_TURISMO_FAZER_LOGIN'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Lista de Avaliações -->
            <?php if (!empty($this->avaliacoes)) : ?>
                <div class="turismo-avaliacoes-lista">
                    <?php foreach ($this->avaliacoes as $avaliacao) : ?>
                        <div class="turismo-avaliacao mb-3" itemprop="review" itemscope itemtype="https://schema.org/Review">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="turismo-rating" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                    <meta itemprop="worstRating" content="1">
                                    <meta itemprop="bestRating" content="5">
                                    <meta itemprop="ratingValue" content="<?php echo $avaliacao->rating; ?>">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <i class="fas fa-star <?php echo ($i <= $avaliacao->rating) ? 'text-warning' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted">
                                    <meta itemprop="datePublished" content="<?php echo HTMLHelper::_('date', $avaliacao->created, 'c'); ?>">
                                    <?php echo HTMLHelper::_('date', $avaliacao->created, Text::_('DATE_FORMAT_LC2')); ?>
                                </small>
                            </div>
                            <div class="mt-2">
                                <strong itemprop="author" itemscope itemtype="https://schema.org/Person">
                                    <span itemprop="name"><?php echo $this->escape($avaliacao->nome); ?></span>
                                </strong>
                                <p class="mb-0" itemprop="reviewBody"><?php echo $this->escape($avaliacao->comentario); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="text-muted"><?php echo Text::_('COM_TURISMO_SEM_AVALIACOES'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Registra o acesso
    $.ajax({
        url: '<?php echo Route::_('index.php?option=com_turismo&task=local.registrarAcesso&format=json'); ?>',
        type: 'POST',
        data: {
            id: <?php echo $this->item->id; ?>,
            '<?php echo Session::getFormToken(); ?>': 1
        }
    });
});
</script>
