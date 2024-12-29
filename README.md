# Documentation de l'API de Paiement

## Description

Cette API permet d'effectuer un paiement via le service MTN Mobile Money. Vous pouvez envoyer une requête pour initier un paiement en spécifiant le montant et le numéro de téléphone du payeur.

---

## Endpoint

### POST `/mtn-momo/paiement`

Ce point d'entrée permet d'initier une transaction de paiement.

---

## Corps de la requête

Le corps de la requête doit être envoyé au format JSON et contenir les champs suivants :

| Champ    | Type   | Obligatoire | Description                                       |
| -------- | ------ | ----------- | ------------------------------------------------- |
| `amount` | Float  | Oui         | Le montant à payer (par exemple : 55.0).          |
| `phone`  | String | Oui         | Le numéro de téléphone du payeur (format MSISDN). |

### Exemple de corps de la Requête

```json
{
    "amount": 55.0,
    "phone_number": "123456789"
}
```

---

## Réponses

### Succès :

-   **Statut HTTP :** `202 Accepted`
-   **Corps :**

```json
{
    "message": "Request-to-pay sent successfully",
    "reference_id": "",
    "status_code": 202,
    "details": {
        "headers": {},
        "original": {
            "message": "Request-to-pay status fetched successfully",
            "details": {
                "financialTransactionId": "",
                "externalId": "",
                "amount": "55",
                "currency": "EUR",
                "payer": {
                    "partyIdType": "MSISDN",
                    "partyId": "123456789"
                },
                "payerMessage": "Taillor-mate Laravel MTN Payment",
                "payeeNote": "Thank you for using Taillor mate Softwares MTN Payment",
                "status": "SUCCESSFUL"
            }
        },
        "exception": null
    }
}
```

### Erreur :

-   **Statut HTTP :** `400 Bad Request` (si des champs sont manquants ou invalides)
-   **Statut HTTP :** `500 Internal Server Error` (en cas d'erreur inattendue)

**Exemple de réponse d'erreur :**

```json
{
    "message": "Une erreur inattendue s'est produite",
    "details": "Détails de l'erreur."
}
```

---

## Notes

1. Assurez-vous que le numéro de téléphone est valide et dans le bon format.
2. Le montant doit être un nombre positif.
3. Cette API utilise le service sandbox de MTN Mobile Money pour les tests.

---
