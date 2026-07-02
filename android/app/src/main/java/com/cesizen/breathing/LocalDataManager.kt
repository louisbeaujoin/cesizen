package com.cesizen.breathing

import android.content.Context
import android.content.SharedPreferences
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import java.text.Normalizer

data class LocalExercise(
    val id: Int,
    val name: String,
    val description: String,
    val inspirationDuration: Int,
    val apneaDuration: Int,
    val expirationDuration: Int,
    val isActive: Boolean = true
)

data class LocalInfoPage(
    val id: Int,
    val title: String,
    val slug: String,
    val content: String,
    val sortOrder: Int = 0,
    val isPublished: Boolean = true
)

object LocalDataManager {

    private const val PREFS_NAME = "cesizen_data"
    private const val KEY_EXERCISES = "exercises"
    private const val KEY_PAGES = "info_pages"
    private const val KEY_NEXT_EX_ID = "next_ex_id"
    private const val KEY_NEXT_PAGE_ID = "next_page_id"

    private val gson = Gson()

    private fun prefs(context: Context): SharedPreferences =
        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)

    // ── Exercices ────────────────────────────────────────────────────────────

    fun getExercises(context: Context): MutableList<LocalExercise> {
        val json = prefs(context).getString(KEY_EXERCISES, null)
        if (json != null) {
            val type = object : TypeToken<MutableList<LocalExercise>>() {}.type
            return gson.fromJson(json, type)
        }
        val defaults = mutableListOf(
            LocalExercise(1, "Méthode 7-4-8", "Technique de relaxation profonde. L'inspiration longue suivie d'une rétention et d'une expiration encore plus longue favorise la détente et aide à trouver le sommeil.", 7, 4, 8, true),
            LocalExercise(2, "Méthode 5-5", "La cohérence cardiaque classique. Un rythme régulier de 6 respirations par minute, idéal pour réduire le stress au quotidien.", 5, 0, 5, true),
            LocalExercise(3, "Méthode 4-6", "Variante apaisante avec une expiration plus longue que l'inspiration. Particulièrement efficace pour calmer l'anxiété.", 4, 0, 6, true)
        )
        saveExercises(context, defaults)
        prefs(context).edit().putInt(KEY_NEXT_EX_ID, 4).apply()
        return defaults
    }

    fun getActiveExercises(context: Context): List<LocalExercise> =
        getExercises(context).filter { it.isActive }

    private fun saveExercises(context: Context, list: List<LocalExercise>) =
        prefs(context).edit().putString(KEY_EXERCISES, gson.toJson(list)).apply()

    fun addExercise(context: Context, name: String, description: String, inspiration: Int, apnea: Int, expiration: Int, isActive: Boolean): LocalExercise {
        val list = getExercises(context)
        val id = prefs(context).getInt(KEY_NEXT_EX_ID, list.size + 1)
        val ex = LocalExercise(id, name.trim(), description.trim(), inspiration, apnea, expiration, isActive)
        list.add(ex)
        saveExercises(context, list)
        prefs(context).edit().putInt(KEY_NEXT_EX_ID, id + 1).apply()
        return ex
    }

    fun updateExercise(context: Context, id: Int, name: String, description: String, inspiration: Int, apnea: Int, expiration: Int, isActive: Boolean): Boolean {
        val list = getExercises(context)
        val idx = list.indexOfFirst { it.id == id }
        if (idx == -1) return false
        list[idx] = LocalExercise(id, name.trim(), description.trim(), inspiration, apnea, expiration, isActive)
        saveExercises(context, list)
        return true
    }

    fun deleteExercise(context: Context, id: Int): Boolean {
        val list = getExercises(context)
        val removed = list.removeAll { it.id == id }
        if (removed) saveExercises(context, list)
        return removed
    }

    fun toggleExercise(context: Context, id: Int): Boolean {
        val list = getExercises(context)
        val idx = list.indexOfFirst { it.id == id }
        if (idx == -1) return false
        list[idx] = list[idx].copy(isActive = !list[idx].isActive)
        saveExercises(context, list)
        return true
    }

    // ── Pages d'information ──────────────────────────────────────────────────

    fun getInfoPages(context: Context): MutableList<LocalInfoPage> {
        val json = prefs(context).getString(KEY_PAGES, null)
        if (json != null) {
            val type = object : TypeToken<MutableList<LocalInfoPage>>() {}.type
            return gson.fromJson(json, type)
        }
        val defaults = mutableListOf(
            LocalInfoPage(1, "Qu'est-ce que la santé mentale ?", "sante-mentale", "La santé mentale est un état de bien-être dans lequel une personne peut se réaliser, surmonter les tensions normales de la vie, accomplir un travail productif et contribuer à la vie de sa communauté.\n\nElle est aussi importante que la santé physique et concerne chacun d'entre nous.", 1, true),
            LocalInfoPage(2, "Comprendre le stress", "comprendre-le-stress", "Le stress est une réaction naturelle de l'organisme face à une situation perçue comme menaçante ou exigeante.\n\nLes signes du stress\n- Physiques : tensions musculaires, maux de tête, fatigue\n- Émotionnels : irritabilité, anxiété\n- Comportementaux : difficultés de concentration, isolement", 2, true),
            LocalInfoPage(3, "La cohérence cardiaque", "coherence-cardiaque", "La cohérence cardiaque est une technique de respiration contrôlée qui synchronise le rythme cardiaque avec la respiration.\n\nBénéfices : réduction du cortisol, amélioration du sommeil, renforcement immunitaire.\n\nLa règle des 365 : 3 fois par jour, 6 respirations/min, 5 minutes.", 3, true),
            LocalInfoPage(4, "Techniques de relaxation", "techniques-de-relaxation", "Il existe de nombreuses techniques de relaxation :\n\n- Respiration abdominale\n- Relaxation musculaire progressive (Jacobson)\n- Méditation de pleine conscience (Mindfulness)\n- Visualisation guidée\n- Cohérence cardiaque", 4, true),
            LocalInfoPage(5, "Quand consulter un professionnel ?", "quand-consulter", "Consultez un professionnel lorsque :\n- Le stress persiste au-delà de quelques semaines\n- Vous avez du mal à accomplir vos activités\n- Vous vous sentez constamment triste\n- Vous vous isolez socialement\n\nNuméros utiles :\n- SOS Amitié : 09 72 39 40 50\n- Numéro suicide : 3114", 5, true),
            LocalInfoPage(6, "Bien dormir pour mieux gérer le stress", "bien-dormir", "Conseils pour améliorer son sommeil :\n- Couchez-vous à heures fixes\n- Éteignez les écrans 1h avant le coucher\n- Pratiquez la cohérence cardiaque\n- Évitez la caféine après 15h\n- Chambre fraîche (18-19°C) et sombre", 6, true),
            LocalInfoPage(7, "Activité physique et santé mentale", "activite-physique-sante-mentale", "L'exercice physique libère des endorphines, réduit le cortisol et améliore le sommeil.\n\nActivités recommandées :\n- Marche rapide 30 min/jour\n- Course, natation, vélo\n- Yoga, tai-chi\n\nObjectif OMS : 150 min d'activité modérée par semaine.", 7, true),
            LocalInfoPage(8, "Alimentation et gestion du stress", "alimentation-stress", "Aliments anti-stress :\n- Magnésium : chocolat noir, amandes, noix\n- Oméga-3 : poissons gras, graines de lin\n- Probiotiques : yaourt, kéfir\n- Vitamine C : agrumes, kiwi\n\nÀ limiter : caféine, sucres rapides, alcool.", 8, true)
        )
        saveInfoPages(context, defaults)
        prefs(context).edit().putInt(KEY_NEXT_PAGE_ID, 9).apply()
        return defaults
    }

    fun getPublishedPages(context: Context): List<LocalInfoPage> =
        getInfoPages(context).filter { it.isPublished }.sortedBy { it.sortOrder }

    private fun saveInfoPages(context: Context, list: List<LocalInfoPage>) =
        prefs(context).edit().putString(KEY_PAGES, gson.toJson(list)).apply()

    fun addInfoPage(context: Context, title: String, content: String, sortOrder: Int, isPublished: Boolean): LocalInfoPage {
        val list = getInfoPages(context)
        val id = prefs(context).getInt(KEY_NEXT_PAGE_ID, list.size + 1)
        val slug = makeUniqueSlug(list, slugify(title))
        val page = LocalInfoPage(id, title.trim(), slug, content.trim(), sortOrder, isPublished)
        list.add(page)
        saveInfoPages(context, list)
        prefs(context).edit().putInt(KEY_NEXT_PAGE_ID, id + 1).apply()
        return page
    }

    fun updateInfoPage(context: Context, id: Int, title: String, content: String, sortOrder: Int, isPublished: Boolean): Boolean {
        val list = getInfoPages(context)
        val idx = list.indexOfFirst { it.id == id }
        if (idx == -1) return false
        val slug = makeUniqueSlug(list.filter { it.id != id }, slugify(title))
        list[idx] = LocalInfoPage(id, title.trim(), slug, content.trim(), sortOrder, isPublished)
        saveInfoPages(context, list)
        return true
    }

    fun deleteInfoPage(context: Context, id: Int): Boolean {
        val list = getInfoPages(context)
        val removed = list.removeAll { it.id == id }
        if (removed) saveInfoPages(context, list)
        return removed
    }

    fun toggleInfoPage(context: Context, id: Int): Boolean {
        val list = getInfoPages(context)
        val idx = list.indexOfFirst { it.id == id }
        if (idx == -1) return false
        list[idx] = list[idx].copy(isPublished = !list[idx].isPublished)
        saveInfoPages(context, list)
        return true
    }

    private fun slugify(text: String): String {
        val normalized = Normalizer.normalize(text, Normalizer.Form.NFD)
            .replace(Regex("\\p{InCombiningDiacriticalMarks}+"), "")
        return normalized.lowercase()
            .replace(Regex("[^a-z0-9\\s-]"), "")
            .replace(Regex("\\s+"), "-")
            .replace(Regex("-+"), "-")
            .trim('-')
    }

    private fun makeUniqueSlug(existing: List<LocalInfoPage>, base: String): String {
        if (existing.none { it.slug == base }) return base
        var counter = 1
        while (existing.any { it.slug == "$base-$counter" }) counter++
        return "$base-$counter"
    }
}
