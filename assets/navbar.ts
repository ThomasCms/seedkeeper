import axios from 'axios';
import Routing from 'fos-router';

export function updateLocale(locale: string) {
    const url = Routing.generate('set_locale', { _locale: locale });
    axios.post(url).then(() => {
        location.reload();
    }).catch((e) => {
        console.error('Cannot change locale.');
    });
}
