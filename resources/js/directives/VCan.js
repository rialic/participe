export default (() => {
    function mounted(element, binding) {
        const { arg, value } = binding

        if (isValidArguments(arg, value)) {
            const abilities = arg
            const isAbilityEnable = isValueValidForAbilities(value, abilities)

            if (!isAbilityEnable) {
                element.remove()
            }
        }
    }

    function isValidArguments(arg, value) {
        return Array.isArray(arg) && arg.length > 0 && isValidValue(value)
    }

    function isValidValue(value) {
        return (
            (Array.isArray(value) && value.length > 0) ||
            (typeof value === 'string' && value.trim().length > 0)
        )
    }

    function isValueValidForAbilities(value, abilities) {
        if (Array.isArray(value)) {
            return value.every((val) => abilities.includes(val.trim()))
        }

        return abilities.includes(value.trim())
    }

    return {
        mounted
    }
})()