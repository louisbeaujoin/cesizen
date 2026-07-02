package com.cesizen.breathing

data class BreathingExercise(
    val name: String,
    val description: String,
    val inspiration: Int,
    val apnea: Int,
    val expiration: Int,
    val cycles: Int = 6
) {
    val cycleDuration: Int get() = inspiration + apnea + expiration

    companion object {
        val PRESETS = listOf(
            BreathingExercise(
                name = "Méthode 7-4-8",
                description = "Technique de relaxation profonde. L'inspiration longue suivie d'une rétention et d'une expiration encore plus longue favorise la détente et aide à trouver le sommeil.",
                inspiration = 7,
                apnea = 4,
                expiration = 8
            ),
            BreathingExercise(
                name = "Méthode 5-5",
                description = "La cohérence cardiaque classique. Un rythme régulier de 6 respirations par minute, idéal pour réduire le stress au quotidien.",
                inspiration = 5,
                apnea = 0,
                expiration = 5
            ),
            BreathingExercise(
                name = "Méthode 4-6",
                description = "Variante apaisante avec une expiration plus longue que l'inspiration. Particulièrement efficace pour calmer l'anxiété.",
                inspiration = 4,
                apnea = 0,
                expiration = 6
            )
        )
    }
}
