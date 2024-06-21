let iv: any;
let cipherText;
export function classNames(...classes: string[]) {
    return classes.filter(Boolean).join(" ");
}

export function camalize(str: string) {
    return str
        .toLowerCase()
        .replace(/[^a-zA-Z0-9]+(.)/g, (m, chr) => chr.toUpperCase());
}

export const checkRoleMatch = (parentArray: string[], subsetArray: string[]) => {
    if (!subsetArray) {
        return false
    }
    return subsetArray.every((el: any) => {
        return parentArray.includes(el)
    })
}

export const booleanCheck = (check: any) => Boolean(parseInt(check))

export const currency = (value: number, decimal: number) => parseFloat(value.toString()).toFixed(decimal)