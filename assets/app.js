/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
const $ = require('jquery');
require('bootstrap');


/************************** DEBUT  */


$("select").change(function(){ // a chaque fois qu'on saisie une autre quantite
    var qte=0
    var total=0

    $( "select option:selected" ).each(function() { // je fais une boucle sur toutes les quantites selectionnées <option>

        var quantites =$(this).val(); //je récupère la valeur des options
    
        if( quantites != "0"){ // si la quantite d'une option n'est pas egale à 0
                    
            qte+=parseInt(quantites); // je fais la somme des quantites
            var prix=$(this).parent().attr("class");// et récupère la valeur de la class de son parent, car sa valeur correspond au prix ttc du produit. C'est comme ca que j'ai fait dans le fichier catalogue.html.twig
            
            total+=parseInt(quantites)*prix ; // je calcule le total ttc

        }
    })

    // je remplace les champs #total et #quantity par le resulatat
    $("#total").text(total.toFixed(2) + " €");
    $("#quantity").text(qte);

})



