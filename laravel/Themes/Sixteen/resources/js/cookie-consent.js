import "vanilla-cookieconsent/dist/cookieconsent.css";
import * as CookieConsent from "vanilla-cookieconsent";

CookieConsent.run({
	categories: {
		necessary: {
			enabled: true,
			readOnly: true,
		},
		functionality: {
			enabled: true,
		},
		analytics: {
			enabled: true,
		},
		marketings: {
			enabled: true,
		},
	},
	guiOptions: {
		consentModal: {
			layout: "cloud inline",
			position: "bottom center",
			flipButtons: false,
			equalWeightButtons: true,
		},
		preferencesModal: {
			layout: "box",
			flipButtons: false,
			equalWeightButtons: true,
		},
	},
	language: {
		default: "en",
		translations: {
			en: {
				consentModal: {
					title: "Cookie Consent",
					description: "We use cookies to make our site work and also for analytics and advertising purposes. See our Cookie Policy for more details.",
					acceptAllBtn: "Accept all",
					acceptNecessaryBtn: "Reject all",
					showPreferencesBtn: "Manage preferences",
				},
				preferencesModal: {
					title: "Manage cookie preferences",
					acceptAllBtn: "Accept all",
					acceptNecessaryBtn: "Reject all",
					savePreferencesBtn: "Accept current selection",
					closeIconLabel: "Close modal",
					sections: [
						{
							title: "Somebody said... cookies?",
							description: "We want to let you know that we are placing cookies on your device that remember your choices.",
						},
						{
							title: "Strictly Necessary cookies",
							description: "These cookies are essential for the proper functioning of the website and cannot be disabled.",
							linkedCategory: "necessary",
						},
						{
							title: "Functionality",
							description: "These cookies collect information about how you use our website. All of the data is anonymized and cannot be used to identify you.",
							linkedCategory: "functionality",
						},
						{
							title: "Performance and Analytics",
							description: "These cookies collect information about how you use our website. All of the data is anonymized and cannot be used to identify you.",
							linkedCategory: "analytics",
						},
						{
							title: "Advertisement Cookies",
							description: "These cookies collect information about how you use our website. All of the data is anonymized and cannot be used to identify you.",
							linkedCategory: "marketings",
						},
						{
							title: "More information",
							description: 'For any queries in relation to my policy on cookies and your choices, please <a href="#contact-page">contact us</a>',
						},
					],
				},
			},
		},
	},
});
