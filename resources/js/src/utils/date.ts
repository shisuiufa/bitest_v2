export function localDate(date: Date | string | null): string | null {
    if (date === null) {
        return null
    }

    const dateObject = new Date(date)

    return dateObject.toLocaleDateString()
}

export function formatDate(time: string): string {
    return new Date(time).toLocaleString().split(',')[0].replaceAll('/', '.')
}

export function localDateTime(date: Date | string | null): string | null {
    if (date === null) {
        return null
    }

    const dateObject = new Date(date)

    return dateObject.toLocaleString()
}
