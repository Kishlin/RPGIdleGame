const timeFormat = (unixTimestamp: number, locale: LangValue): string => (
    new Date(1000 * unixTimestamp)
).toLocaleTimeString(locale);

export default timeFormat;
