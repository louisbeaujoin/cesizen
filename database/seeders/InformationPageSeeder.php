<?php

namespace Database\Seeders;

use App\Models\InformationPage;
use Illuminate\Database\Seeder;

// Crée les pages d'information sur la santé mentale
class InformationPageSeeder extends Seeder
{
    public function run(): void
    {
        // Page 1 : définition de la santé mentale
        InformationPage::create([
            'title' => 'Qu\'est-ce que la santé mentale ?',
            'slug' => 'sante-mentale',
            'content' => "La santé mentale est un état de bien-être dans lequel une personne peut se réaliser, surmonter les tensions normales de la vie, accomplir un travail productif et contribuer à la vie de sa communauté.\n\nElle est aussi importante que la santé physique et concerne chacun d'entre nous. La santé mentale ne se résume pas à l'absence de troubles mentaux : c'est un continuum allant d'un bien-être optimal à des difficultés qui peuvent nécessiter un accompagnement.\n\nPrendre soin de sa santé mentale, c'est aussi apprendre à reconnaître ses émotions, gérer son stress et maintenir des relations sociales positives.",
            'sort_order' => 1,
            'is_published' => true,
        ]);

        // Page 2 : comprendre le stress
        InformationPage::create([
            'title' => 'Comprendre le stress',
            'slug' => 'comprendre-le-stress',
            'content' => "Le stress est une réaction naturelle de l'organisme face à une situation perçue comme menaçante ou exigeante. Il peut être positif (eustress) lorsqu'il nous motive, ou négatif (distress) lorsqu'il devient chronique.\n\nLes symptômes du stress peuvent être physiques (maux de tête, fatigue, troubles du sommeil), émotionnels (irritabilité, anxiété, tristesse) ou comportementaux (isolement, changements d'appétit, difficultés de concentration).\n\nIl est essentiel de savoir identifier les sources de stress et de développer des stratégies d'adaptation pour préserver sa santé mentale et physique.",
            'sort_order' => 2,
            'is_published' => true,
        ]);

        // Page 3 : technique de cohérence cardiaque
        InformationPage::create([
            'title' => 'La cohérence cardiaque',
            'slug' => 'coherence-cardiaque',
            'content' => "La cohérence cardiaque est une technique de respiration qui permet de réguler le système nerveux autonome et de réduire le stress.\n\nElle consiste à adopter un rythme respiratoire régulier, généralement autour de 6 respirations par minute, en contrôlant les durées d'inspiration, de rétention et d'expiration.\n\nLes bienfaits sont nombreux :\n- Réduction du cortisol (hormone du stress)\n- Amélioration de la concentration\n- Meilleure gestion des émotions\n- Renforcement du système immunitaire\n- Amélioration de la qualité du sommeil\n\nIl est recommandé de pratiquer la cohérence cardiaque 3 fois par jour, pendant 5 minutes, soit 3 séries de 6 cycles respiratoires. C'est la méthode dite \"365\".",
            'sort_order' => 3,
            'is_published' => true,
        ]);

        // Page 4 : quand consulter un professionnel
        InformationPage::create([
            'title' => 'Quand consulter un professionnel ?',
            'slug' => 'quand-consulter',
            'content' => "Il est important de consulter un professionnel de santé mentale lorsque :\n\n- Le stress ou l'anxiété deviennent envahissants et persistent au-delà de quelques semaines\n- Vous avez des difficultés à accomplir vos activités quotidiennes\n- Vous vous sentez constamment triste, vide ou désespéré(e)\n- Vous avez des troubles du sommeil importants\n- Vous vous isolez socialement\n- Vous avez des pensées négatives récurrentes\n\nN'hésitez pas à en parler à votre médecin traitant, qui pourra vous orienter vers un psychologue ou un psychiatre.\n\nNuméros utiles :\n- SOS Amitié : 09 72 39 40 50\n- Fil Santé Jeunes : 0 800 235 236\n- Numéro national de prévention du suicide : 3114",
            'sort_order' => 4,
            'is_published' => true,
        ]);
    }
}
