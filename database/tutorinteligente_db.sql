-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 27-10-2025 a las 19:28:51
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tutorinteligente_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos`
--

CREATE TABLE `conceptos` (
  `id` bigint UNSIGNED NOT NULL,
  `seccion` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conceptos`
--

INSERT INTO `conceptos` (`id`, `seccion`, `titulo`, `descripcion`, `url`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'INTRODUCCIÓN', 'INTRODUCCION I', '<h2 class=\"ql-align-center\">What is Lorem Ipsum?</h2><p class=\"ql-align-justify\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><h2 class=\"ql-align-center\">Why do we use it?</h2><p class=\"ql-align-justify\">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><br></p>', 'https://www.youtube.com/', '2025-10-27', '2025-10-27 13:52:34', '2025-10-27 13:53:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'TUTORINTELIGENTE', 'TI', 'logo.webp', '2025-10-06 21:07:41', '2025-10-06 21:07:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuestionarios`
--

CREATE TABLE `cuestionarios` (
  `id` bigint UNSIGNED NOT NULL,
  `seccion` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pregunta` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resp1` varchar(800) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resp2` varchar(800) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resp3` varchar(800) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resp4` varchar(800) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correcta` int NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` json DEFAULT NULL,
  `datos_nuevo` json DEFAULT NULL,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', '{\"ci\": \"123456\", \"id\": 2, \"dir\": \"LOS OLIVOS\", \"fono\": \"6755675675\", \"foto\": \"21761225258.jpg\", \"tipo\": \"DOCTOR\", \"acceso\": \"1\", \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"nombre\": \"JUAN\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"usuario\": \"juan@gmail.com\", \"created_at\": \"2025-10-23T13:14:18.000000Z\", \"updated_at\": \"2025-10-23T13:14:18.000000Z\", \"fecha_registro\": \"2025-10-23\"}', NULL, 'USUARIOS', '2025-10-23', '09:14:18', '2025-10-23 13:14:18', '2025-10-23 13:14:18'),
(2, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ AL USUARIO {\"id\":2,\"usuario\":\"juan@gmail.com\",\"nombre\":\"JUAN\",\"paterno\":\"PERES\",\"materno\":\"MAMANI\",\"ci\":\"123456\",\"ci_exp\":\"LP\",\"dir\":\"LOS OLIVOS\",\"correo\":\"juan@gmail.com\",\"fono\":\"6755675675\",\"acceso\":1,\"tipo\":\"DOCTOR\",\"foto\":\"21761225258.jpg\",\"fecha_registro\":\"2025-10-23\",\"status\":0,\"created_at\":\"2025-10-23T13:14:18.000000Z\",\"updated_at\":\"2025-10-23T13:17:29.000000Z\",\"permisos\":[\"usuarios.api\",\"usuarios.index\",\"usuarios.listado\",\"usuarios.create\",\"usuarios.store\",\"usuarios.edit\",\"usuarios.show\",\"usuarios.update\",\"usuarios.destroy\",\"usuarios.password\",\"estudiantes.api\",\"estudiantes.listado\",\"estudiantes.index\",\"estudiantes.create\",\"estudiantes.store\",\"estudiantes.edit\",\"estudiantes.show\",\"estudiantes.update\",\"estudiantes.destroy\",\"conceptos.api\",\"conceptos.listado\",\"conceptos.index\",\"conceptos.create\",\"conceptos.store\",\"conceptos.edit\",\"conceptos.show\",\"conceptos.update\",\"conceptos.destroy\",\"cuestionarios.api\",\"cuestionarios.listado\",\"cuestionarios.index\",\"cuestionarios.create\",\"cuestionarios.store\",\"cuestionarios.edit\",\"cuestionarios.show\",\"cuestionarios.update\",\"cuestionarios.destroy\",\"puntuacions.api\",\"puntuacions.listado\",\"puntuacions.index\",\"puntuacions.create\",\"puntuacions.store\",\"puntuacions.edit\",\"puntuacions.show\",\"puntuacions.update\",\"puntuacions.destroy\",\"practicas.api\",\"practicas.listado\",\"practicas.index\",\"practicas.create\",\"practicas.store\",\"practicas.edit\",\"practicas.show\",\"practicas.update\",\"practicas.destroy\",\"progresos.api\",\"progresos.listado\",\"progresos.index\",\"progresos.create\",\"progresos.store\",\"progresos.edit\",\"progresos.show\",\"progresos.update\",\"progresos.destroy\",\"configuracions.index\",\"configuracions.create\",\"configuracions.edit\",\"configuracions.update\",\"configuracions.destroy\",\"reportes.usuarios\",\"reportes.r_usuarios\",\"reportes.estudiantes\",\"reportes.r_estudiantes\"],\"url_foto\":\"http:\\/\\/tutorinteligente.test\\/imgs\\/users\\/21761225258.jpg\",\"foto_b64\":\"data:image\\/jpg;base64,\\/9j\\/4AAQSkZJRgABAQAAAQABAAD\\/2wCEAAkGBxISEhISEhAPFRUVFRUPFRUQEBUVFRUVFRUWFhUVFxUYHSggGBolGxUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGhAQGC0fHyUtLysuMC4tLS01LzcrLSsuNTUtNS0tLS4tLS0vODU1LS0vNS0tLS0tLS4tLS0tLS0tLf\\/AABEIAPIA0AMBIgACEQEDEQH\\/xAAcAAACAQUBAAAAAAAAAAAAAAAAAQUCBAYHCAP\\/xABEEAABAwIDBAcEBQoFBQAAAAABAAIDBBEFITESQVFhBgcTInGBkTJCobFygqLB0RQzUlNjkrLh8PEjJHOD0hUXQ2Kj\\/8QAGgEBAAMBAQEAAAAAAAAAAAAAAAMEBQIGAf\\/EACsRAQACAgEDAwIFBQAAAAAAAAABAgMRBBIhMRNBYXGxBSJR0fBigZHB4f\\/aAAwDAQACEQMRAD8A3eAqkk0AhCEAhCECKAboQgaErpoBCEIBCFSSgRKYCA1NA0IQgEIQgEimkgAmkmgEIQgEk0IBCSNpA0kJoBCEIBJNUvcACSQABckmwAGpJQVIWs8e64aWJ7o6WJ9SW5doHBsV+DXHNw5gW4ErXeP9YeJVLyWTzQR7o4Xhtjx7RrWuPmVzN4hJXHaXR5QAuUqqqqps5qmok4drNI\\/4OOSrjq6xoGxV1TQNA2olbbwAdkuPVh36Euq0Lmyi6f4tBYCre9o3TsZJfxc4bf2lmXRXrjkfIyKtp2WcdntaYO7p3F0RJJHEg5cF1F4lxOK0NwJpAosu0ZoSDkBAJoQgErJoQJNCV0DQhJAIsmhAIQkgaEIQC0b1sdP\\/AMofJQU2y6Fvcmku7vvBzawtIBa21je4JvwBO6MTkIilLc3bDrZ2F9k2ud2e9cnUkWyCSQbHZuNDz+ajyW1CbDXcnTs2TzV9DK1vtZ+KowzBqipuYWXAOySTYX4X32usyoerMuYO1mftmxOwAA0cG336Zn0Va96x5lcpWfaGLurW2yNt+QSixBpGe7la\\/ms2n6soiAGvmBF7naBJyy1FgN+Q3Kwd1YWYf8xJt2yOwAwHfdup9VH140n52MTVTXC1go6piBIsspxLq\\/njbtQy7ZA9l4AJ8HDLyPqsXrKKeAMdLG5oeLtJ9dk8Hcl3Saz4lHfq94bY6penr3Pbh9W8uJyp5Xm7jb\\/wvJ1NvZJ1tbhfbq5Imce7I0lpaQ4EagtIII5g2K6l6NYg6opKad7dl0kTHuba1nFo2h63VrHbcKeanTO4SVk0IUiEISTQCEKkuQVJWQmgSaQTQCEIQCEIQJIlJ2aYFkEP0xn7KgrZM+7TzHL\\/AE3Lm\\/otRCeWKE6E3d4MaXEedrea6T6XOaKGsLwC0U8xIdoR2btVz51YR3q7\\/oxm31iAT8\\/VV+ROq7WuL502xgGDsgiYxoGQ4b958Sc1NxxKmBivomLPiFy9tPIQpOgV61iHMXXSh9WUVLByWO9J8BZPC9hGRF8txBuCOGYWYSRqxnZe4XyY0mrfbnfFIOxfLDua8geG74ELofquqnSYXRucbkR9nflG5zG352aFoLp1E9lbMHe9slufugBgv+6SugOrN18LoTa14W35nMX81oYPG1PkfoydCEKdVCSaoOaBkpgIATQCEIQCSaEAhJNAKkhNNAgE0IQQnTKNr6OoiJA7WJ8AJ0Be0tBPK5WkernCpYK6aKVoDo2NDrG4O04FpB3gjO63j0miJiDrXDXBx8LrBqakaKuaVu9kUNt4LHSk\\/B7fRUeTedzVf4tI1Ewy6mar+NqsaVSDFXh9yyrAQ4KtgQ5dq++62kCsagKRlUfVDJcysYpaK6xqJ8uIiOMFz3xsDQN2bszy1JK6G6PU7I6aCOMANZGxgA0FhZa6bhzP+oSVDgCfycRNHMPeXeXsLZGDRFkMbTw\\/srXGvvt8OOVXXf5XyEJK2pFZMBNCASTQgEJWTQCEJXQNJATQCEk0AhCEFE0Qc0tOhBB81gklJ2c1\\/wBI5+LbNP3LPlAdJ6QbAmAza4F1t4Pd08dlVuTj6q7j2WuLk6bdM+7ypypCJyiqd6v43KjErGSq9YUnFebHJOcu9q\\/T3UyuUdVOV3K9RVfLYE8lxaVnFVb4PQCSbbOhOx5NO0fks3UZgFEI4mG3fc25Jv73etyUmFocfH0V+qnyMnXft7FZVISU6A0IQgEIQgFS4X3ppoKXFDQm0JoBCEIBJNCBJpFAQC86qASMcw6OBafNeqEkidMOhu0ljrbTDsG3Lf5jNX0UigsTmP5RK9uYLvC+QzzV3TVdwsee1phsa6qxKabIk6RWLagJOnTqcem95ZFbUtIZpA0juizneF9PO3zVrWVoaOe4K66Fynbm2zm4MI1tlt3HAajJSYoi14iXzJM1xzaGWNCaELUZYQhCBITSKBpICaAQhCBJoQgEJXTQCRQmEAEIQgSxjpd0pFNLTUrG7UtS8M1sI4ybF54nUAcjwzn8Qro4GGSVwa0cd\\/IDeVpV2MfluLU89rNMoawa2Y1jtkfC\\/iSg2zimCtlaHMs14AtfRw4H8ViVXDJG6zmlruB0PMHQrYdOe63wC8cQEZaQ9rXDSxAOe7VV83Hrfv4lZw8i2Pt5hr9lY7Qhw8lW+rNsgSeenqps0DHezI1nJxDvnn8V60tJC1wL9mQ63JBGW\\/Z0WdTom3TF6\\/5+y5bPGt9M\\/RE4XhcsxuBYHV7h3R9Eb1kGJxCjpZJImgujaZRte+4C+ZHHRTcbgRkovpUL00o4sf8AwOWphwxj+qhmz2yefCnonj7K6mjqGDZ2u69hNyx49pt9\\/EHeCFMLS3VX0lZSu7CU2ZMGkO3NeBqeRHyC3Ox4IBBBBzBBuCOIKlQqkISQCaEIBJNCAQkmgEIQgFSCmgoGhYH0i6xY4XmOna2Qj2nuvseAAzd45eaxHEesKrfcCQMH7Nob8dfig3HVVccYvI9jR\\/7OAWKYn0+hDuzpx2r9bnusaBq4nWy05imLyvBc97nHi5xKsqWqcyBrrnamLnk79lpLWN8MifrIMl6ZdJXykgyl5OV9GtHBjdw56lR\\/Qw\\/5uj\\/1mt\\/eBb96xmolJzKneitRsT0r\\/wBGohP\\/ANGj70HR1RMI231tkBxKxTFpXyujDyQNsONtGgG\\/rlrzUo+Uyvvu0aPv8VXWYeNnnxVLmcKeTERN5iP0\\/dNhzelO9bWAw5rrnaHeN\\/LVeFbQNDJBtDNhaLeo+XxUcymLn27SUE397L+SqwyjeZAHPLhnfMm3A2PgvKYcNb5YpvzOmrbqrWbdXiN6TeD4i9oaHAlvA6tHL8FIdJrGkncDpE9\\/lsH7lRHh4tqVCdJq4w0VYx1\\/zEoYeZYQB4XK9fxeNOCnR1zaPbfsyMuSL23rTRskhZ2ZGoAK2J0S6YSMaA14PGKQ9130He4eWngta4m61lRS1BbmCrKN0Vg\\/TWln7pf2T9C2TKxGRF\\/xWRxvBF2kEcQbj1XL2I1bg6GYG3aAsfzcy1neJaQPqqbwzpFUQ2McsjfBxsfJB0QhagoOsqqb7fZv+m2x9W2Wc9FemUNZ3COzlHuE3DubHb\\/DVBkyEIQCRQmgEkJoBYH1r9JDTQNhYbPmve2oYNfU5eRWeLn3rVxLt8QlAN2x2gH1B3vtlyDH4aguuTxVzDDdWFILEjzU\\/RR5IIbF4u7ZUOZtU8DhuaWebXuH9eKksTivkFaUlZHFEYpL7W3tR2YXXJye0bOeRA3akoI2WANF3+gy4aX35n0zXthc9mbX6BD\\/AN0g\\/cvLFIgNXZ29m1tkWsARuNgFRguYcONwg6ZwgBxvyv6qQqx3T4FY50Bq+0pqdx1MTQfpNADviCsirXd0+i5yW6azPw+x5Y9Txf4o0Nr\\/AC9UsOjHaHUZ\\/evemA7W\\/iimykN\\/6z4rxHHnWalv6o\\/01bz2tHwyCMZLBetKQNpJOLtiP96RoPwJ9FnMTslrTrgqLNgjv7Um2Rxaxrr\\/AGnMXumS07i7+8AqIRdeWIO\\/xVI00LA3b2iQPaDWlxHMgZ25r4KsXZaOnbvLnv8AIBoV\\/Rw3ao6qq2zSgsvsNaGNuLX3k28SfQKfw5mSCPkYQqIcTfDI1zHEEWcCDncHIq+rmWUK+EvcbbkHSXRPGhV0sc+VyNl4G57cj+Pmpa61h1L1wAmpic7CdumY9lx+LVtABA0IQgSaEkFFRKGMc86NaXHyF1y7UymSd7nG5c4uJ5k3K6V6RPtS1J\\/YyfwFczR\\/nD4oKpXbLx6KdoHE2+e7LVY1i5V7g2JnswN+85cdbW13IJ7ELNGXtW3jmefgsRZVGKZrzewJB+i4bJt5fJZKZNoZrHcVgQW+LxlriOevEcUYLJY2TJ7SK3vR93xZ7p8tPRWdE\\/ZeEG+OqesvA6P9VM5v1ZLSA+r3DyWeVzslp3qxr9iqdGTlNHl9OIlw+y5\\/ottVj7tbz\\/BVuZbp495+Jd443aFlSHvm44pR+3keKVETtHeM+f8AZDbbbfEj4LxVZ1MT8\\/s05jvP0TkJyWnetes2qwM3RRfakNyPRjPVbdgf3b8rrnjpRiXbTVE98pJHFv0G91n2Whe+jwyWJVr7vKlcKl7NrpT7gy5uOTR6\\/AKGYC53iVIV7vZiGjc3c3n8Bl5lBVhUZJWXU3daoHCYLKVnmsEFNWSfDW\\/w81aQvAa4N1Otxyzvmc9QrDF8TdYRtyuc9NBwyyuvaiN2lBknVbWmPEYszZ5dEbnXaBA+1YroJc1dDH2r6c\\/to\\/4wulLIGhK6aAQhCCwx2Lapqho1MUg+yVzJEO+fFdUSNuCOII9VyzNdsr28HEehQWuKHVeOFmwC9MRzurfD37uBQT9PKvKtbcLyievR7roIdrjG+4HIjiDqF5VsOy4Obm094HkrupjXnHYgsdoc2ngfwKCZwDETG6KZuZie2S28ge03zbtDzW+zUtdGxzTdpG0DxBG0CPIrm3DZTG\\/ZK3B0AxUyUxgJ70BDQOMTgTGbcu836o4qh+KTMcW\\/9vvCbjxvJDLaAi5sfX8VU51ntuPeC8qJ2Zy9PwTnJBFuN\\/6C8bvu05j8636bYr+T0EhabPkAp4+O1JlceDdp31VoDGJbANHgth9Z2M9pM2EHuU4JdwMsmf2WW\\/fctZ27R5J9kZk8l73DO8dZ+I+zIt5lVRjs29oddGDnx8B+HFKljublEjto8AMgOAV3SsUjlK0\\/dC8amW6DIbK2legi6722qVoD3VD1TruClKI5FBNdCItrEKYD9az0DgT8Auk1z51VxbWJwctt3pG4roNAJXTSLeKBpXVJzVQCBrljE3gzykaF7iPDaK6nWrOnnVr2sj6il2WvcS57Dkxzjq4Eew4+h5INPVWaj6Z1nEKfxPAKuE2kpZxzbGXt\\/eZcKG\\/6fPtZU8+Wp7F9vWyC+jkVwHqI7exsbgjUHIjyXuyoQXUwVm9q9jLdeTigHd636Q0PH+ayLotjHYysl3D\\/AA5Rxjda5tyIDvJY0V70k9nX37+f81xkpXJWaW8S+1tNZ3DoSjkzvcFpAIINxY6WKsekmJtp4nynMjJjf0nnJrfX4AlYz1d4ztD8mce80bUJv7Ue9ni35EcCoDpzj3by90gxxEtZbR79HSeA0B8TvXlcP4beeV6VvEd5n4\\/7\\/PDTvyIinXHmWMYtUOe4gu2nOJe9x3ucbuPqVY20a3+5VTO+6wOup+XleyuA4RggG5ORyI0OV+Xz8F61lvJ1OW2vbPNXMWStNu5uVUZkF2+RW0r14PqF5Mc552WNc48GNLj6BB4xm7yedlJwHIqypqGa+cE4POF4+5ZJg\\/RWtqCBHTStBy2pWmNo597MjwBQTnVA4DEo772yAeOw4\\/IFb+WF9AOg0dADI49pO4bJeRYNG9rBuHE6n4LMwgE0IQIJoSQNCEIPCakY7VoUdUdHYXblMJIMPr+gcEuTmxu+m0H5rHa\\/qigdm0OYeLHn5G4W00INI1PU7KPzc7vrsDvkQoyfqnrh7L4neIe37iugE0HN8nVjiY9yA\\/7j\\/wDgvH\\/triX6uEf7jv8AgulkIOfKPoJiDQA7YaQb7Ubn7QysbGwtcZeBK8K\\/q+xF5sxkIaMs3uBt4Bi6JckAg55PV7iAADIYzr7T7b9fZz0B5eZXjH1YYofcgHjK77mLo5CDQVP1R1x9qSFvg1zvnZSlL1NOP5yoeeTGtb87rdCaDWVD1SUrM3M2j+0cXfDT4LJaLoXBGAAGgcGtAHoFlCEEbT4JEz3VexwtGgAXomgQTQkgaEIQCEIQJNCpt6IGmhCAQhCASsmhAkFBQEAmhCAQhCASTQgEkgOKqQCEIQCEIQJF01RqgrQhCBJoQgQTQhAIQhAJOQhA0ihCBoQhAIQhAJIQgaQQhA0IQgEIQgpcm1CEH\\/\\/Z\",\"full_name\":\"JUAN PERES MAMANI\",\"full_ci\":\"123456 LP\",\"fecha_registro_t\":\"23\\/10\\/2025\",\"usuario_abrev\":\"juan@gma...\"}', '{\"ci\": \"123456\", \"id\": 2, \"dir\": \"LOS OLIVOS\", \"fono\": \"6755675675\", \"foto\": \"21761225258.jpg\", \"tipo\": \"DOCTOR\", \"acceso\": 1, \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"nombre\": \"JUAN\", \"status\": 1, \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"usuario\": \"juan@gmail.com\", \"created_at\": \"2025-10-23T13:14:18.000000Z\", \"updated_at\": \"2025-10-23T13:14:18.000000Z\", \"fecha_registro\": \"2025-10-23\"}', NULL, 'USUARIOS', '2025-10-23', '09:17:29', '2025-10-23 13:17:29', '2025-10-23 13:17:29'),
(3, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ESTUDIANTE', '{\"ci\": \"1231231\", \"id\": 3, \"dir\": \"LOS OLIVOS\", \"fono\": \"62323232\", \"foto\": \"31761226146.jpg\", \"tipo\": \"DOCTOR\", \"acceso\": \"1\", \"ci_exp\": \"LP\", \"correo\": \"eduardo@gmail.com\", \"nombre\": \"EDUARDO\", \"materno\": \"\", \"paterno\": \"GONZALES\", \"usuario\": \"eduardo@gmail.com\", \"created_at\": \"2025-10-23T13:29:06.000000Z\", \"updated_at\": \"2025-10-23T13:29:06.000000Z\", \"fecha_registro\": \"2025-10-23\"}', NULL, 'ESTUDIANTES', '2025-10-23', '09:29:06', '2025-10-23 13:29:06', '2025-10-23 13:29:06'),
(4, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN ESTUDIANTE', '{\"ci\": \"1231231\", \"id\": 3, \"dir\": \"LOS OLIVOS\", \"fono\": \"62323232\", \"foto\": \"31761226146.jpg\", \"tipo\": \"ESTUDIANTE\", \"acceso\": 1, \"ci_exp\": \"LP\", \"correo\": \"eduardo@gmail.com\", \"nombre\": \"EDUARDO\", \"status\": 1, \"materno\": \"\", \"paterno\": \"GONZALES\", \"usuario\": \"eduardo@gmail.com\", \"created_at\": \"2025-10-23T13:29:06.000000Z\", \"updated_at\": \"2025-10-23T13:34:25.000000Z\", \"fecha_registro\": \"2025-10-23\"}', '{\"ci\": \"1231231\", \"id\": 3, \"dir\": \"LOS OLIVOS #2\", \"fono\": \"62323232\", \"foto\": \"31761226146.jpg\", \"tipo\": \"ESTUDIANTE\", \"acceso\": \"1\", \"ci_exp\": \"LP\", \"correo\": \"eduardo@gmail.com\", \"nombre\": \"EDUARDO\", \"status\": 1, \"materno\": \"\", \"paterno\": \"GONZALES\", \"usuario\": \"eduardo@gmail.com\", \"created_at\": \"2025-10-23T13:29:06.000000Z\", \"updated_at\": \"2025-10-23T13:35:45.000000Z\", \"fecha_registro\": \"2025-10-23\"}', 'ESTUDIANTES', '2025-10-23', '09:35:45', '2025-10-23 13:35:45', '2025-10-23 13:35:45'),
(5, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ AL USUARIO eduardo@gmail.com', '{\"ci\": \"1231231\", \"id\": 3, \"dir\": \"LOS OLIVOS #2\", \"fono\": \"62323232\", \"foto\": \"31761226146.jpg\", \"tipo\": \"ESTUDIANTE\", \"acceso\": 1, \"ci_exp\": \"LP\", \"correo\": \"eduardo@gmail.com\", \"nombre\": \"EDUARDO\", \"status\": 1, \"materno\": \"\", \"paterno\": \"GONZALES\", \"usuario\": \"eduardo@gmail.com\", \"created_at\": \"2025-10-23T13:29:06.000000Z\", \"updated_at\": \"2025-10-23T13:35:45.000000Z\", \"fecha_registro\": \"2025-10-23\"}', '{\"ci\": \"1231231\", \"id\": 3, \"dir\": \"LOS OLIVOS #2\", \"fono\": \"62323232\", \"foto\": \"31761226146.jpg\", \"tipo\": \"ESTUDIANTE\", \"acceso\": 1, \"ci_exp\": \"LP\", \"correo\": \"eduardo@gmail.com\", \"nombre\": \"EDUARDO\", \"status\": 0, \"materno\": \"\", \"paterno\": \"GONZALES\", \"usuario\": \"eduardo@gmail.com\", \"created_at\": \"2025-10-23T13:29:06.000000Z\", \"updated_at\": \"2025-10-23T13:35:51.000000Z\", \"fecha_registro\": \"2025-10-23\"}', 'USUARIOS', '2025-10-23', '09:35:51', '2025-10-23 13:35:51', '2025-10-23 13:35:51'),
(6, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN CONCEPTO', '{\"id\": 1, \"url\": \"url\", \"titulo\": \"INTRODUCCION I\", \"seccion\": \"INTRODUCCIÓN\", \"created_at\": \"2025-10-27T13:52:34.000000Z\", \"updated_at\": \"2025-10-27T13:52:34.000000Z\", \"descripcion\": \"<h2 class=\\\"ql-align-center\\\">What is Lorem Ipsum?</h2><p class=\\\"ql-align-justify\\\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><h2 class=\\\"ql-align-center\\\">Why do we use it?</h2><p class=\\\"ql-align-justify\\\">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><br></p>\", \"fecha_registro\": \"2025-10-27\"}', NULL, 'CONCEPTOS', '2025-10-27', '09:52:34', '2025-10-27 13:52:34', '2025-10-27 13:52:34'),
(7, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ UN CONCEPTO', '{\"id\": 1, \"url\": \"url\", \"titulo\": \"INTRODUCCION I\", \"seccion\": \"INTRODUCCIÓN\", \"created_at\": \"2025-10-27T13:52:34.000000Z\", \"updated_at\": \"2025-10-27T13:52:34.000000Z\", \"descripcion\": \"<h2 class=\\\"ql-align-center\\\">What is Lorem Ipsum?</h2><p class=\\\"ql-align-justify\\\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><h2 class=\\\"ql-align-center\\\">Why do we use it?</h2><p class=\\\"ql-align-justify\\\">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><br></p>\", \"fecha_registro\": \"2025-10-27\"}', '{\"id\": 1, \"url\": \"https://www.youtube.com/\", \"titulo\": \"INTRODUCCION I\", \"seccion\": \"INTRODUCCIÓN\", \"created_at\": \"2025-10-27T13:52:34.000000Z\", \"updated_at\": \"2025-10-27T13:53:30.000000Z\", \"descripcion\": \"<h2 class=\\\"ql-align-center\\\">What is Lorem Ipsum?</h2><p class=\\\"ql-align-justify\\\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><h2 class=\\\"ql-align-center\\\">Why do we use it?</h2><p class=\\\"ql-align-justify\\\">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><br></p>\", \"fecha_registro\": \"2025-10-27\"}', 'CONCEPTOS', '2025-10-27', '09:53:30', '2025-10-27 13:53:30', '2025-10-27 13:53:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_31_165641_create_configuracions_table', 1),
(2, '2024_11_02_153317_create_users_table', 1),
(3, '2024_11_02_153318_create_historial_accions_table', 1),
(4, '2025_10_06_164053_create_conceptos_table', 1),
(5, '2025_10_06_164154_create_cuestionarios_table', 1),
(6, '2025_10_06_164312_create_puntuacions_table', 1),
(7, '2025_10_06_164342_create_practicas_table', 1),
(8, '2025_10_06_164420_create_progresos_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicas`
--

CREATE TABLE `practicas` (
  `id` bigint UNSIGNED NOT NULL,
  `nivel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seccion` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lineas` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progresos`
--

CREATE TABLE `progresos` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `progreso` double NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntuacions`
--

CREATE TABLE `puntuacions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `puntuacion` double NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acceso` int NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `correo`, `fono`, `password`, `acceso`, `tipo`, `foto`, `fecha_registro`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', '', '0', '', '', '', '', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 1, 'ADMINISTRADOR', NULL, '2025-10-06', 1, '2025-10-06 21:07:41', '2025-10-06 21:07:41'),
(2, 'juan@gmail.com', 'JUAN', 'PERES', 'MAMANI', '123456', 'LP', 'LOS OLIVOS', 'juan@gmail.com', '6755675675', '$2y$12$or9sW4UJnFkhkPPpwrkNhO1ZH5EH3I.05wEbcvmq3hG1PJcJKccdS', 1, 'ADMINISTRADOR', '21761225258.jpg', '2025-10-23', 1, '2025-10-23 13:14:18', '2025-10-23 13:17:29'),
(3, 'eduardo@gmail.com', 'EDUARDO', 'GONZALES', '', '1231231', 'LP', 'LOS OLIVOS #2', 'eduardo@gmail.com', '62323232', '$2y$12$jngjS3R.0KCgTHbsbN6czOvPtTy7twcp1COWmNNxKo6kZ/owKLAPe', 1, 'ESTUDIANTE', '31761226146.jpg', '2025-10-23', 1, '2025-10-23 13:29:06', '2025-10-23 13:35:51');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `conceptos`
--
ALTER TABLE `conceptos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuestionarios`
--
ALTER TABLE `cuestionarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_accions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `practicas`
--
ALTER TABLE `practicas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `progresos`
--
ALTER TABLE `progresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `puntuacions`
--
ALTER TABLE `puntuacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `puntuacions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `conceptos`
--
ALTER TABLE `conceptos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuestionarios`
--
ALTER TABLE `cuestionarios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `practicas`
--
ALTER TABLE `practicas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `progresos`
--
ALTER TABLE `progresos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `puntuacions`
--
ALTER TABLE `puntuacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD CONSTRAINT `historial_accions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `puntuacions`
--
ALTER TABLE `puntuacions`
  ADD CONSTRAINT `puntuacions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
