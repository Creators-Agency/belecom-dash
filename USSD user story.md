-----------check solar panel serial umber -------------
if:yes
	-- we need 2 things from serial 
		1 . to access solar type which hold price 
		2 . user information
		#in USSD next will be displaying menu

			#1IF DECIDE TO PAY MONTHLY
			--access client *Account* depending on his account id
				------------check if has Account ------------
				if:yes
					From here we can access info like 
					1 . month to paid
					2 . loan
					3 . month left
					4 . overdue month
					---check if there is overdue payment
						if:no
						---PERFORM SELL
							total amount = TotalPrice / Mo$installment
							Call PAYMENT API
						else:END with message to pay "an overdue"
				else:END

			#2IF DECIDED TO PAY CUSTOM INSTALLMENT
			---access client *Account* depending on her account id
				------------check if has Account ------------
				if:yes
					From here we can access info like 
					1 . month to paid
					2 . loan
					3 . month left
					4 . overdue month
					---check if there is overdue payment
						if:no
						---PERFORM SELL
							---check if month entered is <= to monthlef
							if:yes
								Initial amount = TotalPrice / Installment
								TotalAmount = Initial amount * CustomMonth
								CALL PAYMENT API
							else:END probably redirect to level above to re-enter custom month
						else:END with message to pay "an overdue"
				else:END

			#3IF DECIDED TO PAY AMOUNT DUE (temporary study)
			---access client *Account* depending on her account id
				------------check if has Account ------------
				if:yes
					From here we can access info like 
					1 . month to paid
					2 . loan
					3 . month left
					4 . overdue month
					---check if there is overdue payment
						if:yes
							Initial amount = TotalPrice / Installment
							initial Penalty = (30 * initialAmount)/100
							month to pay = Initial amount * monthOverdue
							total penality = initial penality * monthOverdue
							TotalAmount = month to pay + total penality
							---ToProcced input Agent code(this code should not be used more that one), if exist--------
							if:yes 
								TotalAmount 
								CALL PAYMENT API
							else:END with feedback
						else:END with feedback
				else:END with feedback


