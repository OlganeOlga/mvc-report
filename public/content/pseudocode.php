START

    CREATE OBJEKT carddesk
    CREATE OBJEKT bank

    DOE bankSuffle(carddesk)

    READ number of palyer (INT)
    DOE bank READ bankSet(INT)

    FOR i < number of palyer (INT)
        CREATE palyer(OBJEKT)
        DOE bank(carddesk, palyer) deal card
        player CALCULATE points
        player READ playerSet(INT)
    ENDFOR
    
    FOR each player
        WHILE player is not sutisfyed
            DOE bank(carddesk, palyer) deal card
            player(card) CALCULATE points

            CASE points > 21
            CALCULATE playerVinst = playerVinst - playerSet
            CALCULATE bankVinst = bankVinst + playerSet
            BREAK -> ENDWHILE

            CASE points = 21
            CALCULATE playerVinst = playerVinst + playerSet
            CALCULATE bankVinst = bankVinst - playerSet
            BREAK -> ENDWHILE

            CASE points < 21
            IF player is not sutisfyed THEN
                REPEAT form DOE ...
            ELSE
                GO TO bank take revanshe
        ENDWHILE

        SEQUENCE bank take revanshe
        BEGIN
            DOE bankTakeCard(carddesk)
            CALCULATE points bank(card) 

            CASE points > 21
            CALCULATE playerVinst = playerVinst + playerSet
            CALCULATE bankVinst = bankVinst - playerSet
            BREAK -> ENDWHILE
        
            CASE points = 21
            CALCULATE playerVinst = playerVinst - playerSet
            CALCULATE bankVinst = bankVinst + playerSet
            BREAK -> ENDWHILE

            CASE points < 21
            IF player is not sutisfyed THEN
                REPEAT form DOE ...
            ELSE
                GO TO bank compare points with player
        END

        SEQUENCE bank compare points with player
        BEGIN
            IF bankpoints >= palyerpoints THEN
                CALCULATE playerVinst = playerVinst - playerSet
                CALCULATE bankVinst = bankVinst + playerSet
            ELSE
                CALCULATE playerVinst = playerVinst + playerSet
                CALCULATE bankVinst = bankVinst - playerSet
            ENDIF
        END
    ENDFORE
END