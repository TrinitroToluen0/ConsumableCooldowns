[![](https://poggit.pmmp.io/shield.state/ConsumableCooldowns)](https://poggit.pmmp.io/p/ConsumableCooldowns)

# ConsumableCooldowns
Set cooldowns to ender pearls and enchanted golden apples

# Setup
1. Download and place the .phar in your `plugins` folder.
2. Restart your server.

# Permissions
`consumablecooldowns.bypass` Bypass the cooldowns of the consumable items.


# Config
```yaml
# The message to send to the player who is on cooldown. Use {TIME} to display the time left in seconds.
cooldown-message: §cThis item is on cooldown, wait {TIME} seconds to use it again!

# Here you can set the cooldown of the enchanted golden apple, in seconds. Set to 0 to disable.
enchanted-gapple-cooldown: 180

# Here you can set the cooldown of the ender pearl, in seconds. Set to 0 to disable.
ender-pearl-cooldown: 15
```