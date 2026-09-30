export const CONTACT_PHONE = '+8801868196716';
export const CONTACT_EMAIL = 'mbscfirm@gmail.com';

const WHATSAPP_NUMBER = CONTACT_PHONE.replace('+', '');

export const whatsappUrl = (text = ''): string =>
    `https://wa.me/${WHATSAPP_NUMBER}${text ? `?text=${encodeURIComponent(text)}` : ''}`;

export const mailtoUrl = (subject = '', body = ''): string => {
    const params = [
        subject && `subject=${encodeURIComponent(subject)}`,
        body && `body=${encodeURIComponent(body)}`,
    ].filter(Boolean);

    return `mailto:${CONTACT_EMAIL}${params.length ? `?${params.join('&')}` : ''}`;
};

/** Build a plain-text enquiry from labelled form values, skipping empty ones. */
export const enquiryText = (fields: Record<string, string>): string =>
    Object.entries(fields)
        .filter(([, value]) => value.trim() !== '')
        .map(([label, value]) => `${label}: ${value.trim()}`)
        .join('\n');
