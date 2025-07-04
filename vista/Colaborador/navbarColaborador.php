<?php
require_once(__DIR__ . '/../../logica/Colaborador.php');
require_once(__DIR__ . '/../../logica/Cuenta.php');
require_once(__DIR__ . '/../../logica/Solicitud.php');
require_once(__DIR__ . '/../../logica/Usuario.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_GET["cerrarSesion"]) || !isset($_SESSION["colaborador"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: /puntos-reciclaje/index.php");
    exit();
}
$colaborador = $_SESSION["colaborador"];
$cuenta = $_SESSION["cuenta"] ?? ($colaborador && method_exists($colaborador, 'getCuenta') ? $colaborador->getCuenta() : null);
?>

<!-- Estilos del navbar ecológico -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Inter', sans-serif;
    }

    .navbar {
        background: linear-gradient(135deg, #ccfc7b 0%, #28a745 100%);
        box-shadow: 0 8px 16px rgba(40, 167, 69, 0.2);
        padding: 0.75rem 3rem;
        border-radius: 0 0 15px 15px;
        margin-bottom: 2rem;
    }

    .navbar .nav-link {
        color: #155724 !important;
        font-weight: 500;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .navbar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.3);
        color: #0c4128 !important;
    }

    .navbar-brand {
        font-weight: 600;
        color: #0c4128 !important;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
    }

    .navbar-brand img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }

    .navbar-text {
        color: #0c4128;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .btn-logout {
        background-color: #28a745;
        color: white !important;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        border: none;
        transition: background-color 0.3s ease;
    }

    .btn-logout:hover {
        background-color: #218838;
        color: #fff !important;
    }

    .navbar-toggler {
        border-color: rgba(21, 87, 36, 0.5);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%230c4128' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.5%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .dropdown-menu {
        border-radius: 10px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item:hover {
        background-color: #d4edda;
        color: #0c4128;
    }
</style>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="/puntos-reciclaje/vista/Colaborador/indexColaborador.php">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAA8FBMVEX///8zey6BrSWEryR4qAAveS4ueC5/rCAweit+qxt8qhWFsCQqdyQldR96qQB+qx4fcxh4pyXm7tb2+e/5+/RyoyZChCzX5NamxG1PjCr4+/gachEhcxp9qiVpnSfq8dy3z4rg6s1ZkilimCiUuUrT4bhSjU5unmvI2qaMtDs/giywy39glV2tx6s8gTeev17k7eSTtZHJ2siFrIO5z7jQ37S/1Jenw6W0zYaZvFSgwWPb58WpxnPh6+Fck1l/qHwAbABHhkOauZhgmABYlBNpnz+Ms3KpxZzU4s1OjAy2zLg6gRd/qFu60K2evnSsxojk5xGJAAAZTElEQVR4nO1daVvqSrYmJCFJMQ8KBBVQEILzAIqibj19+/a553b3//83XUOmmjIRYffzuL7s7ZYNeVnTu1atqioUfuRHfuRHfuRHfuRHfuRHsNjt1utyNbrYQOn1NpuL59V8Oh629/1cOUhn+Dq/mJRrmqYBACxP4N+Bhv7N6a2Wrf9aoO3paF0EEJlRlIphAa1mTDbzlr3vx00p7eXGqWmgLscWholwFl9WrX0/dVKxxytH06wIzQkFogS96e9vsfb42alF2WU0Ss24Wf7WIIcjR8sMD4tRh5p83TcOiXSWky3huSAtzRoN942Gl/ZzUcsBnisAvPxmimz1aiA3eFgsbbLcN6pAxmvNyhcfEkOz5p19Q8PSovE1D5q5gdSK8/0zgWEvhE8tnp0d5IaviPTo7NlW2xdhfAf9flPNEyDBON4jwHnRjy9q8eQwf3wYY623LxLQmmgBvjPze/AhscB8H/jsZ80j1urxaemwWY7ThVGvk+KpbhjpUic01d3T8rHjGSjEpy8O5PpTVUiqtbrjTNY3Ly+9Xu/lZj1xMNNOWHxAAbXVjgGOaq4W1OKpXjoT40PYwB9/+5+//+/jP9pM2O+0YXE8ulFhGZygDAFryAB2yeSGgQLPlFL/mAeoqkbzb3/+9f7wf2/Rb9Uez1EpGckYjNq48KrVtd1549JyrUs9WZQWJyw+VT0+OPuren53nzRdD5ebopy1GxbMF3P4nWq9HXGcCzeEqsf9UumUg3dwetgYvF/dpnvTTmslAWkAGGWm2GiA87qcL1+/OXm0J56FnpZKiwMqgsKk2DerjferbN81Ki85c62r0P/mbt1ioI6WpjmrbwTZUskzqAeLEu2BavGgb+pH13cxnhcprz1AFymWgwGyarVW30VYp64hqaclXQ97YLl5apYqg/PHbT+5vbJChabhQG2tGIDFb8yQ7mepB2YJ5ngfIKJsJb0ymHVz+ZS542PUYJB55gFijNNcPoyWkQvwVNdL/cA8j89MiK+REz4kU8dFZT23L4QAUQ7Jv+wgn6UeH5aUIMlDSmOWFHPwnh8+JEuV+AOIaB7U8u50vBCAB4quKx5LQ5RNV8zG533OHwY9woptjGj5+mIPuBaq6Au3jIclhaIrSqXy9B2Rrb2pxfA5w8iTybkA+9BCD90PKENKo0ADnaXM7onltShXowFgKq4X80uML/izjiGkUp9kefUA+iNUoPmY24fwMpK1KC2n3TbQH3kRuQ32waapK6VT1wWRAypKY/a9HCqo0liAmMkZtZxcEacJFGM8gIjSKMhCr/L5ALl0eoK6A6luqiFTHReGvc32XzJO9OoJ1FnpDJsoojQQYPVzG4aWUOa8EsG6g5kc4qwtWErfbPsZUwKwpLhpUG1iD1QaH7voZm44HYIbu7CqoTgzLIxh2qxPtvyIVs0HiE20fIJSBLTQr1wQxMkLixDcEKtCvviKeIG2Jblpq4YPsEwsFCnQrOaf5IXCItQ2hKqCCfRF9OVvTVAnFgosCGAfafCYWGjFzJelyYWxUu2CRHYEcIncB2zL3Z4BAgjNUj9UEUszdQzw+ruyPCcjIAS4tnFdbGxNTvHX1EQAFzhJ6ATg+e5WTF7DtYW2KhRucMuG+CJucWwlQ2QiWG9m000YOwZY6NRCAOcFew0BAhcgiqat7Zibg7pqhygRwmoCh5tdAwyFGgMC7KA2ETJVFGwsFaULsA1zQz6AyDZOhOrZXgAWxp4Sa9NC24FwtRHxRZQu2tAXrUnmB0LvjZOD3g8BvN71qqWrRBhT2kX4V9Tg7+FoisxzXiMZMpPYjkHyhGIe+yZqmjuLop4MkRINmBWG0PFwsEGVDkoXSBBnRmabRS7g+xwjVKWDAGB1B1SUFcRBtVdIQevQF5cFG/kiWPuPiX77nOV9W/B/qjjKnKo4IyIZ7IjJ0ALLbzAfuwwNBxsUTT1B6SPT2tSkTnxPX6g4I2KyvRsuygkEATQv2Lj50Bcb0a4M3A2ZBnZC/QAyGQKw+pHfQ6cSDAIvQrVR053xuw6OsGnJTRuR0AWxUZwRkRPuOE+EpAODjKGO3WAzYn6LqwMrJb2BYYZUEWax3C+5TriHKOPJ0LIgBD+asr+FPmpYqcjN0LPR0knZTYRK49tbFlHSUtuwVEVcW7RSilK39ZLm/WCWVRe4oih7YbQyy+lZM4oN610UbCB1EyhrqhWtNf/PUoGZgnAYmAoXrhOa+57AGrv1bscR0bS5VkszW7Suk1yv98v9vWbCsNxYmNlAbgpE2kqlgDFUIUamN10uk4uNtrcr5p41AMakbAKbLR8FqhDneL1/TPApZmV7OjpWt5uKsZ9f8DoFpmmjrR4FemEZp8DS8aFHZp62ekckK1joaDd5dOER8RakjBTSs1w20/ds1Dzf+rF6uBsBnByWi3Cdv00PEVYrmHFDvuba6PZhBj8UkrqVA0RM07boQT0DV4WHXhytXIpeN06+Oavjz6jk0DwquDQtVXagnqboqfDUBag0RM3RtlYDxuTiNYlnrUMtQaOYgxaHBmQ3IOMbLTWiQmWxUKIyxQqtbhlAUy9iP2hD9TzrxRzCzRgSuLqTrc3mGGUvgnpeKGTcjrd4CbReNEZ2IMbaerGoQPqo2RZIIeduliiAYhUOQ3qxQFTsHnPzItuFeldQDyrTmsUIqKeMCoVLFMuw5RlRia7Ir1TnsnS70iwtQ7CxHeOYwifLhRfMAJo0fowE69R1J/2T8TJfZxk5HWteY9SThngUYcJoxpAkumGNB4j78/uSC6AuKCM1JR1gbnLdEC8gcCucruxrx0+naBzQKqxK2mu8aixH8F20hCqEAXjXo+meQCPt03GmISkqvCefBAP3QNB6lqnQyG0SJqXASEoDNIWEreAjrG9CDsmHtrZksjCHxemM4hgnieJMoeDpBkyD7QQGNxWxCgdSZxL6YesJimzS5ox0IGsOeJTGel4GeuIU44QjrtMLbyHR9rKdaQmYZCiuKpD4s0qg3fMVxSqGShXGehR2yv3sZtpYjJEeSefy/FklGF8C1sKMfFJGWn+h5pvStTfzEqfMGKm8PROwTdBu+U8ORtSLbsJmaW2mNL3JJ5ra9u1bt3sPpRtv9+1aMRFjQ9IJzG0TVA+GI34Nft0zzcG1LevETvfx62F2fl1pNAaDBpRBNXbWfFpjjLR6J39xQEzho754P1DxY0jpDKyGNMLMnRb77fHusjIYHFUrFdMMm9yvmNWxZ8CUFY2I/kzAVqxNoaO6rkgtclGzMDC0tCmCw1h0UnTdp9l1tVGlkIUCR3Rfd11e0K+vRDWS176T1Yb+xAQVIenJSW3aoX62etK3lsnt1UwZVCticEQiBwo7xWPaSKWEBkvgVVCJnitSilnRCMcFap9bPc1KCpTu1+fgKBIdsbuI3vUwjRsWQimxiHpCN/gnipvSNSR0V4oA8AwoQm6/zqFlxqHDDx2hxNcay7qjp9TbPkKkRGKCVhghPcEMg9A6nD2M5GXw42VCeMjw3uXvswKMGw5iFiuCSIIiP7baCIQ1m/6HpAjf7hqNSkJ4CKEpz7ObJq1C5Sjuw/08iJRIVoVGUoSAMdtkCLuzajU5PCQRCWDCuGGC1Qp/31UNpe+1RcfSC1Zlq7QI798HKdRHpCpfRSoz2bCSYLhk5O0yQ6G/Deh8SDWhjDWTPuJj6f3nIKn3hR9bmhI7NaYTHPFlBDJ3dyfhFvu0RnEammn33LH/8D9E4jtvZMCHOkuydxxqzEtjQqkrLbJZkDzvRTn8K4rTgGemORzNad5mWfSHRVrTjv9gAo1wQUYgcwuPnCEldqiij+KlqPdEEdMoXmo//ErtfwFCWQqY/kEHGqWadGW7vXJqAPBGR9UWYMm0bSJqiyslZfykNSMLpvM/mSZUmvmS1wvRhsdwfYhiUHhou6jK8tbbZWYDJQhl3rX6i0GYchq4zesknB1wIy50qIC0xv9KkyB03eReLeVtz4xlmJ+pAIqkFbJKHGyd0M/iPs3beyMptpKuLP768/8/WIgVGZ2+YF4ZRfCSSohq48G6cHNV2HO4qiZRoF4qKYenJ02tvBoW/sn6rDSP/4t5b3GfzXZ7Im+3SWw4bKbI7QIeJ8z3doIUAdEt+idNtVwDG9yB/jenQxnCv7MIhdyg83T9C3VFjirX57Ov+5hwG6QHwtGeg9aHoOfd/YwLodAyD8+aRVWt15y5awS9nBFCub/EscA0K9VG3CktN/5+EFwN+jo1ivz/eoxToF6C8FQVHcm3Dtihw/4v6YPPWIRyWtqd+fHOrDQGM/lxGD6LqePFe5/HCRYQHwYx8Banx2XUJQA1anAAMEQlF4QIY4g0VhrKg4z/eErE9ZVPTA2uWWpfRlqorh+eFHETBAB6+KOtfQ9CRIxDRmVWBu/iPOt5ImGhnko5Fd5+RsVQveQdlmZp7HDLODlCNq/EDlw+Xoe/drNhCs9WcIMLSX8uU+UWnrqVCIC6As2T4KvxG7fnvJXKVJMaYcG+o+ob8+hagNEmrVQSO0lrh5v8uo/owuj6qXvSj1F7EVDZC7YzkRxhdC+RSPeTYiAQI2+rpIYipXGb2CizxH0vLwQD+yxqjnBWb80sy0OED5KnfWARJhq5fKJDvDngzznB3RyXlyPaBhhGGpElSofeUVTAErO8TvmERSjlpXfZeCmbps3BBxsn0QJjjTjQBI2j0b9/lGYJXfHOwTEEDkiEa/JChLJtE08swoS1hf3B6KDKnQYyAYZFYK3r7ODNvVSDwVFbQHUnBDqcoS5rZyxCaX34eMS8MvFs9yMTKLgTXToT4I6i9ICaEKCun5Q9BXqb7+Yj7rM37GqSbEwNfRZbthwl3gXUZbNZRaHV2Jm4rcMLZii0KwsywVFboOgqcOwIXJFb1I3oYnRZf4haW2PEfmcMwBzQAc0ekT/nNMBbySJZcEqMf5hguyea0xsC9ZBDKPOuWxbhUZqNTg+cBZzHG7l9LQaoKwe+hbr9qrkmnJddair7FvJuos0+Y8zSEyNf7BdUqcTawLmYyeiBhbqz/cO1tmYiNMk+sODk0qGcqbBfaMp9Mk8sRDNut9uHmGwHh8FpLwTWHGhcJ4/4JGDnfSMVc8mmfKm6xfLIRY2BjF1g4b4SF+Cpb6Ej/Lr2ROO3Mi+xciGT59JhRCObJTVyl5VB5B65MZO/BRe7XYD+aXeuC04NSzA2TZog/ERzZCP7ik2I0sSSHOLRpQxiR7xgXTrxaQwJnRc1Q1AuL0f4D8dgx9Qi27x8Qky9a5SHWJVtHn4XRhn/RM06GatG56WKWjqEN6BuJRdKI+j0beKWjlx435Kco/EltNGSd16hRXLDq2oJz9iZkwJzBIp87RTl+5/M95GlJ8zlRaX6LoDYFQP0NGiRkWp0yI5of1PHtVvHSBVo+D5G5OSGTPgUUOFby7YicsIAINHghSY5c+6ZVCqvtSLnhtHDB2x1ETGaKBebdzD+pIIHUSb0oyjZ7tO5AeQgaE6GGtlDivdjM28SbXf3HC3JcoiCzbckGkwS5j4IA/TzIEAA8ZnM9P60f7h/3pByGq3VJW9huM/GfiGZDqMR+Bh9/KDNOjwG2PfSBC4gh+h4CNpEH93ktdRInFmBIrs5JNbs3tlPTroMTMuTAGL4jdhuAhLdOzQUHYYI8wBah6Oj6JU7VtABJH2g83PYTWixM0DcR6cj375wIYuyBq6IwXLscVHEZMhRLVQefPTy3MatpvF8OQswLvxz/mEqmRDafNkXovEs/8Uq9BIh3qX9yh+md/XLrS2mWpAq+FwRNdWGn4z7dlMTNyICzukXGqIw44dR3IcjACkuevXL9a+2t0MTNSn5XBHrVpfsd5/RTEWFkechgjCDD/TDToiengCkqokrf/j3xtvsiFR4zL5RfGjkyHe2aCqs3t0V1ysRm/GdcOgCpCc7nn55jzHX0Hxcgazx8JE0krJhueW++hTNGkoEtjhAhmYLSgqfyyAPw9PVFrWw8dTwnAX+EgReKGjRxD/teeLlxjjh44n5CRXxxFoJOQ6OkLUbdNSOwe70exocub7ScQyDBFJ8m8ABF0kTWBxH3JLPDTHCmwM6e0IQZQMbVdtkp32Rajl+DSpeolhbboTt4MNwuTiTJGrc8qVBxlgjyOtmRaTCwEan+DwoJk98NfxD4kaaN+BAluxYgMkiP0drlKOMR+8I6ofqEx9I/TiKbHSNVt9AOIx+DXzKh0+mwzwOL7vycSZZtcdFU+Uo67kmgqgpCDO6dxWd1SYbU6jTkO4G/tTLNDiYhmzr5HWRqCfBJ/2sCYNvT4rEDzMwwxPbU0NRBtbTpkICAdnB4hbGYhUmXGjhhqiyH/L1mGSAyw0zhkOIJjVlDDVIcgyqNfxjn4d4Oo6rDBPXenztk12JoiqJFn99QhuTKYbwhg00f+JWzxigO+yHbZRXYXKGyaXEDD03V4T8hRbXCes3JE8YoQ3hHw3/28UADdK7IBsEuOWKFHN4/GOZ1fzCqUSFoIWPRgxvlsY9LUJShiSJYDZDdlgJVJhsaBs/Fh8gMufEr5hBNdOLoxuyFSM0+4006LY/WngzNaGqRNU851ZMc6vHykps2tGTXMEqITnCxlADgChrETKDz5b3mBxRNU9nUiU1PmFkZ6d8tR8W3eNrXmnhkxmsQVJwLcnoNImx7q4brgEF3SGNJwn6KIOMV5FIll9cgH1qu15oMHqGeQdOU+59T6Qgdq/vYs7uQBJX3NNyy1OPrMcmCttqnpSYm3VrHuGe4a8YGU7nxd3XiEcbp0SdgjCT9vkESqzG1paJ38pX4YI+IMVPhUSDiKJ4Vy0SJ/Q2qjb590qnQnGZmvHMtjd5rCmdMCokdM2bw4Qf6F2aR7TrbYrnC98M+eyLL3KyMhueQPhCxxkvU7gAqw+eheITkt2sL7bRLNWBQIkZ46k0JTJxhvTyfYDm+VL1JqhxlBl6J4xwC/fZvn0RaR5kKqOkZlqib7gmXmi/u1+IfhAMvaPftPzNKBzjzhjpRUu02Xr8sqEgkwJIXM325k8CLkDC6Ni7Q1iQ6zPuDOEmpLAxZDnKgi/HiJJoI8WT7oEGF/4v8dUHr94dbGWBEyq/srWtRUt8lSynfEqqRCaSIjrjazAo/Mm5ev49nSrXxleyH6Qu7IpleTNuPMAFQQE0AGIH3iuDiRO8WBpcYynIhJCvZT3LRzjZmuVkfWG+0A+pdA82YQ0e+gBhpg+SRtG9yIeWLa5GEw5lZXg/bhIJo6DvutbGgQYDQo4GFlqhOwJ5Ohp1DFK8iCcH00dmoSPqdK5wQhsuqImMZeieR57LKPHHIkTK/S/BO5qpVzJEYRndexJ2w0kwphg0+aEPboIbENW+IMpse4bzHU/eMkAUxazA1fDDN0Mv8fIkmNitcmCh6qEI4DY2ikU4mpUaIt9HV/TTMMKmEtifR3XATWcUvolUqMHtzxm/FW5mMZM3fbAIcn44G6rNUIj0Zk7ABX1/pVCDmQvzkAgnXxQzHUXlF7TCxa8a1qBHZuqbi/AdpMeiKJrTpSmCOgp/eWm6b4JgGiKlYYA+mXGc0KkhalMMMKfLtWbi6qeR4uvjg2kQaPD1poFq6SxJpEy9JPQtZe0AMmJLxs2rn4nfn+8p+hlBpdZxGaLjvkREtpHkdiFFR1L+VJSkn8C3J71QSgNkkmRUEFVyuc3Ak65kd6CZmKRyHQM3lDIAfTIT74Lp3CQeomzrTuM9maVy9I8kPQYg2z4lFioBWM332htxzlASX1rNVRc4WTAA2aLfvale8sHb30dBi3SPoDlIcm81N2pVOuYB+jPegQLFMRQB/Mz9Xh/5NshKAo/nVi901b/z0wd4SgP0LlbeEcAoiErjPK5PwtE2U2X7Efgq17CBkqvNdwcwcreu2ZhFRxwO4aLMaJBZw4AGKlWgfAvHthK1pbwyeIjCyFX5h2xHiSIz6slCjk+pSrfhbC3dqIMnq5UIjMI+RlhC5aJajMQXd0LpdvJ2HfWk1eqHbFttLMJgtK14tpClQCzR2+G2FvtSXGm4gg7HEFqQpCnsi0dm1OZphP8pKDl9+wV+H9HzI2bj+k6gyBiEeh/f2n58ciiPn1gq5g5uf3uKOSbHrA7en1iPjF7Nh2RGLasnfTPSPJVU5cw20lXinAqC/Pzqhs01Yg1RQY3F45O+HgcP2seurkG1Z/GTTma1cT178lFGj/CZh/Hw0Fvu8A7Np18Jhg7NSnVgvj9coZPSs5+B6MvR505vQX07T3ZsnGlWjgaDavwAX+wbpeoL5SJfac4W3Rpg9TPbCuFW0s14fmoGqQzu9nMH6lXU2U75idl439s9xLcfO1BjVXAu0w6le77dSaPx+PZloIHcfydGWJPt5TIaRh4/v8lWK/JaZdfy+Jn+WPE4gczvt8GH5H42iL+WIQ2+hnn3O9hnWN4ezNyMtdI4v9p3fBGJfXU+SHxFQxS8xsceCExCebv73AqkWWlUI85C/T2ke/d5dJQp7kB45uzxd/M+obw9XTZirrsRoBtcP0Qe1/ubid39mino2ptYmPgw4qPPj8ed9CfyFfvt8eHyutpoVIVAITSEzfyMP1D6txb7tvv49fF+XR0gaRDBf4XQHp7u3/Z1HWn+4l//hk477761/4tc7kd+5Ed+5Ed+5Ed+5Ee+X/4DNpUuUc+u9WIAAAAASUVORK5CYII=" alt="Logo" width="36" height="36">
        </a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-dark"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Colaborador/actualizarDatos.php">
                        <i class="bi bi-pencil-square"></i> Actualizar datos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Colaborador/residuosColaborador.php">
                        <i class="bi bi-trash2-fill"></i> Gestionar residuos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Colaborador/registroPublicidad/registrarPublicidad.php">
                        <i class="bi bi-megaphone-fill"></i> Publicar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Colaborador/registro_P_Recolect/registrarP_Recolect.php">
                        <i class="bi bi-geo-alt-fill"></i> Registrar punto
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/puntos-reciclaje/vista/Colaborador/solicitud/verSolicitudes.php">
                        <i class="bi bi-list-check"></i> Ver solicitudes
                    </a>
                </li>

            </ul>

            <span class="navbar-text me-2">
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($colaborador->getNombre()); ?>
            </span>
            <a class="btn btn-logout" href="/puntos-reciclaje/index.php?cerrarSesion=1">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
        </div>
    </div>
</nav>
