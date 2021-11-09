import pandas as pd
import xlsxwriter
import random

#create file
names = pd.read_excel("names.xlsx")
gen_f = xlsxwriter.Workbook('generateV3.xlsx')
gWrite = gen_f.add_worksheet()
generate = pd.read_excel('generateV3.xlsx')
id_nat =[]
id_user =[]
count = 0

#generate device ID
gWrite.write(0,0,"User ID")
def gen_id():
    id  =random.randrange(10000,100000)
    if id in id_user:
        gen_id()
    else:
        gWrite.write(i,0, id )
        id_user.append(id)
for i in range(1,10000):
    gen_id()

#generate national ID
gWrite.write(0,1,"ID national")
def gen_idNat():
    id = random.randrange(9411012345, 9972012345)
    if id in id_nat:
        gen_idNat()
    else:
        gWrite.write(i, 1, id)
        id_nat.append(id)
for i in range(1,10000):
    gen_idNat()

#generate names
gWrite.write(0, 2,"full name")
for i in range(1,10000):
    f = random.randrange(0,787)
    s = random.randrange(0,59)
    th = random.randrange(s,59) #third name
    l = random.randrange(0,2900)
    gWrite.write(i,2,str(names['first'][f]) + " " + str(names['second'][s]) + " " + str(names['second'][th] )+ " " + str(names['family'][l] ))

#generate family numbers
gWrite.write(0, 3,"family member")
for i in range(1,10000):
    num = random.randrange(2,10)
    gWrite.write(i, 3,num)

#writing head name
gWrite.write( 0, 4, "seasons" )
seasons = ["Summer", "Autumn", "Winter", "Spring"]
gWrite.write(0, 5,"total meters per week")
gWrite.write(0, 6,"total meters per month")
gWrite.write(0, 7,"total meters per cycle")
Sn_factor=1

#generate season factor
for i in range( 1, 100000 ):
    season = seasons[random.randrange( 4 )]
    gWrite.write( i, 4, season )
#generate useage of water with seasons
    lt   = random.uniform(float(90.5),float(120.9))
    M_Lt = random.uniform( float( 90.5 ), float( 120.9 ) )
    C_Lt = random.uniform( float( 90.5 ), float( 120.9 ) )
    if season == "Summer":
        Sn_factor = random.randrange(12, 14)
    if season == "Spring":
        Sn_factor = random.randrange(10, 12)
    if  season == "Autumn":
        Sn_factor = random.randrange(8, 10)
    if season == "Winter":
        Sn_factor = random.randrange(6, 8)

    
#  السطرين الي تحت لانشاء كمية استهلاك بمعامل زيادة حسب اعداد العائلة نسبة المعامل = (عدد الافراد + 10 / 200)
    F=  "(D" + str(i+1) +"*" + str(lt)   +"*" + str(Sn_factor) +"*7/10000)"
    G=  "(D" + str(i+1) +"*" + str(M_Lt) +"*" + str(Sn_factor) +"*30/10000)"
    H= "(D" + str(i + 1) + "*" + str(C_Lt) + "*" + str(Sn_factor) + "*90/10000)"


    gWrite.write(i, 5, "="+F+"*(D"+str(i+1)+"+10)/200+"+F)
    gWrite.write(i, 6, "="+G+"*(D"+str(i+1)+"+10)/200+"+G)
    gWrite.write(i, 7, "="+H+"*(D"+str(i+1)+"+10)/200+"+H)

for i in range(10000,100000):
    gWrite.write(i,0,"=A"+str(i%10000+2))
    gWrite.write(i,1,"=B"+str(i%10000+2))
    gWrite.write(i,2,"=C"+str(i%10000+2))
    gWrite.write(i,3,"=D"+str(i%10000+2))

gen_f.close()