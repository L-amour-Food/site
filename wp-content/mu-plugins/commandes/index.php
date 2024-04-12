<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <style>.is-48x48 img {
        width:100%;
        height:100%;
        object-fit:cover;
    }
    .red {

    }
    .commande  {
        transition:all 0.5s ease;
        cursor:pointer
    }
    .commande[data-traitee="true"]  {
        background-color:lightgreen;
        opacity: 0.6;
    }
    

    .commande .card-content {
        position:relative;
    }
    .commande .card-content:after {
        position:absolute;
        top:.5em;
        right:.5em;
        font-size:1em
    }
    .commande:not(.payee) .card-content:after {
        content:'Non payée 🚫';
    }
    .commande.payee .card-content:after {
        content:'Payée 🪙';
    }
    </style>
    <script>
        document.addEventListener('click',e => {
            const commande = e.target.closest('.commande');
            if(!commande) return;
            fetch('/wp-json/custom/v1/commande-traitee/'+commande.dataset.id).then(response => response.json()).then(response =>{
                commande.dataset.traitee = response.etat
            })
        })
    </script>
</head>

<body>
<div class="container">
<div class="section">
    <img src="/wp-content/uploads/2020/11/logo-l-amour-food-metz.png">
        <h1 class="title">Commandes</h1>
        <p class="subtitle">Commandes passées dans les <?=$voir_commandes;?> derniers jours.<br><a href="/wp-admin/?voir-commandes=<?=$autres;?>">Afficher les commandes des <?=$autres;?> derniers jours</a></p>
        <p><?=count($commandes);?> commande(s) affichée(s)</p>
        <?php foreach ($commandes as $commande) { ?>
            <div class="card commande <?=$commande['payee'] ? 'payee' : '';?>" data-id="<?=$commande['id'];?>" data-traitee="<?=$commande['etat']?'true' : 'false';?>">
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-48x48">
                                <img src="<?=$commande['photo'];?>" alt="Placeholder image" />
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4"><?=$commande['nom'];?></p>
                            <p class="subtitle is-6"><?=$commande['total'];?></p>
                        </div>
                    </div>

                    <div class="content">
                    <ul>
                    <?php foreach($commande['achats'] as $achat) {?>
                        <li><?=$achat['nom'];?> <span class="has-text-danger"><?=$achat['quantite']>1 ? (' x '.$achat['quantite']) : '';?></span></li>
                    <?php }?>
                    </ul>
                    <time>Commande passée le <?=$commande['date'];?></time>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
</body>

</html>