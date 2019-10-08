import urllib2
import json
import RPi.GPIO as GPIO
from time import sleep
from datetime import datetime,time, timedelta
import mysql.connector as mariadb
import array as arr
import serial


# Constantes y variables globales
grid=0
load=0
pv=0

sonda=23
aerotermia=24

Texterior=True
Activada=True

activoBD=True
sondaBD=True
FhisteresisExecedentes=datetime(1972,06,24,00,00,00)
Fvuelta=datetime(1972,06,24,00,00,00)

HoraInicioPV=time(10,00,00)
HoraFinPV=time(13,00,00)

HoraInicioForzada=time(13,00,00)
HoraFinForzada=time(13,00,00)

consumoDesactivoAerotermia=500
excedentesSonda=-500
excedentesAerotermia=-3000
excedentesAerotermi1=-3000
excedentesAerotermi2=-3000
minutosHisteresis=10
consumoMedioArranque=-10
periodoConsumoMedio=10

segundosBucle=10

consumoRed=arr.array('f')
consumoRedAvg=arr.array('f')
indiceMuestra=0


def printf(texto,valor=""):
        trazas=open("trazasAero.txt","a")
        trazas.write("["+str(ahora)+"]->" +str(texto)+ str(valor) + "\n")
        trazas.close()
        sentencia="SELECT VALOR from trazasBD WHERE DATO='"+str(texto)+"'"
        cursor.execute(sentencia)

        existe=False
        for traza in cursor:
            existe=True

        if existe:
                sentencia="update trazasBD set VALOR='["+str(ahora)+"] "+str(valor)+"' where DATO='"+str(texto)+"'"
                cursor.execute(sentencia)
        else:
                sentencia="insert into trazasBD values('"+str(texto)+"','["+str(ahora)+"] "+str(valor)+"')"
                cursor.execute(sentencia)


# Funciones
def ObtenerDatosInversor():
        global grid
        global load
        global pv

        try:
                req = urllib2.Request("http://192.168.0.157/solar_api/v1/GetPowerFlowRealtimeData.fcgi")
                opener = urllib2.build_opener()
                f = opener.open(req,timeout=20)
                fronius = json.loads(f.read())
                grid =  fronius['Body']['Data']['Site']['P_Grid']
                load =  fronius['Body']['Data']['Site']['P_Load']
                if load < 0:
                        load = load*(-1)

                pv =  fronius['Body']['Data']['Site']['P_PV']

         #      req = urllib2.Request("http://192.168.0.157/solar_api/v1/GetInverterRealtimeData.cgi?Scope=Device&DeviceId=1&DataCollection=CommonInverterData")
         #       opener = urllib2.build_opener()
         #       f = opener.open(req,timeout=20)
         #       fronius = json.loads(f.read())
         #       voltaje =  fronius['Body']['Data']['UAC']['Value']

                sql= "INSERT into registroDatos values(%s,%s,%s,%s)"
                val=(ahora,pv,grid,load)
                cursor.execute(sql,val)

                mariadb_connection.commit()
                return True
        except Exception:
                return False


def ObtenerDatosBD():

        global activoBD
        global sondaBD
        global Fvuelta
        global consumoDesactivoAerotermia
        global excedentesSonda
        global excedentesAerotermi1
        global excedentesAerotermi2
        global minutosHisteresis
        global HoraInicioForzada
        global HoraFinForzada
        global HoraInicioPV
        global HoraFinPV
        global consumoMedioArranque
        global grid

        cursor.execute("SELECT VALOR from aerotermiaCNF WHERE DATO='Activo'")
        for estado in cursor:
            activoBD = estado[0]
            print("activoBD: ",activoBD)

        cursor.execute("SELECT VALOR from aerotermiaCNF WHERE DATO='Sonda'")
        for estado in cursor:
            sondaBD = estado[0]
            print("sondaBD: ",sondaBD)

        cursor.execute("SELECT VALOR from Fechas WHERE DATO='FechaActivacion'")
        for fecha in cursor:
           Fvuelta=fecha[0]
           print("Fvuelta: ",Fvuelta)

        cursor.execute("SELECT VALOR from excedentesCNF WHERE DATO='consumoDESAerotermia'")
        for valor in cursor:
           consumoDesactivoAerotermia=valor[0]
           print("% consumoDesactivoAerotermia: ",consumoDesactivoAerotermia)

        cursor.execute("SELECT VALOR from excedentesCNF WHERE DATO='excedentesSonda'")
        for valor in cursor:
           excedentesSonda=-1*valor[0]
           print("excedentesSonda: ",excedentesSonda)


        cursor.execute("SELECT VALOR from excedentesCNF WHERE DATO='excedentesAerotermi1'")
        for valor in cursor:
           excedentesAerotermi1=-1*valor[0]
           print("excedentesAerotermi1: ",excedentesAerotermi1)

        cursor.execute("SELECT VALOR from excedentesCNF WHERE DATO='excedentesAerotermi2'")
        for valor in cursor:
           excedentesAerotermi2=-1*valor[0]
           print("excedentesAerotermi2: ",excedentesAerotermi2)

        cursor.execute("SELECT VALOR from excedentesCNF WHERE DATO='minutosHisteresis'")
        for valor in cursor:
           minutosHisteresis=valor[0]
           print("minutosHisteresis: ",minutosHisteresis)

        cursor.execute("SELECT VALOR from excedentesCNF WHERE DATO='consumoMedioArranque'")
        for valor in cursor:
           consumoMedioArranque=valor[0]
           print("consumoMedioArranque: ",consumoMedioArranque)

        cursor.execute("SELECT VALOR from excedentesCNF WHERE DATO='periodoConsumoMedio'")
        for valor in cursor:
           periodoConsumoMedio=valor[0]
           print("periodoConsumoMedio: ",periodoConsumoMedio)

        cursor.execute("SELECT VALOR from horasCNF WHERE DATO='horaInicioForzada'")
        for valor in cursor:
           HoraInicioForzada=datetime.strptime(valor[0],"%H:%M:%S").time()
           print("HoraInicioForzada: ",HoraInicioForzada)

        cursor.execute("SELECT VALOR from horasCNF WHERE DATO='horaFinForzada'")
        for valor in cursor:
           HoraFinForzada=datetime.strptime(valor[0],"%H:%M:%S").time()
           print("HoraFinForzada: ",HoraFinForzada)

        cursor.execute("SELECT VALOR from horasCNF WHERE DATO='horaInicioPV'")
        for valor in cursor:
           HoraInicioPV=datetime.strptime(valor[0],"%H:%M:%S").time()
           print("HoraInicioPV: ",HoraInicioPV)

        cursor.execute("SELECT VALOR from horasCNF WHERE DATO='horaFinPV'")
        for valor in cursor:
           HoraFinPV=datetime.strptime(valor[0],"%H:%M:%S").time()
           print("HoraFinPV: ",HoraFinPV)

        #cursor.execute("SELECT VALOR from excedentesCNF WHERE DATO='grid'")
        #for valor in cursor:
        #   grid=valor[0]
        #   print("grid: ",grid)

        mariadb_connection.commit()


def ConsumoRedMedio(referencia):

        global grid
        global load
        global consumoRed
        global consumoRedAvg
        global indiceMuestra
        global periodoConsumoMedio
        consumo1 = float(referencia)
        numMuestras=periodoConsumoMedio*60/segundosBucle
        if indiceMuestra>=numMuestras:
                indiceMuestra=0

        pgrid=0
        if consumo1>0:
                pgrid=100*grid/consumo1
        else:
                pgrid=100
        if len(consumoRed)>indiceMuestra:
                consumoRed[indiceMuestra]=pgrid
                consumoRedAvg[indiceMuestra]=grid
        else:
                consumoRed.append(pgrid)
                consumoRedAvg.append(grid)

        consumo=0
        red=0
        i=0
        if len(consumoRed)<numMuestras:
                numMuestras=len(consumoRed)

        while i<numMuestras:
                consumo=consumo+consumoRed[i]
                red=red+consumoRedAvg[i]
                i=i+1

        consumoRedMedio=consumo/numMuestras
        redMedio=red/numMuestras
        indiceMuestra=indiceMuestra+1
        printf("Consumo medio: ",consumoRedMedio)
        printf("Excedente/carga medio red: ",redMedio)
        return consumoRedMedio

# Inicio

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

GPIO.setup(sonda, GPIO.OUT)
GPIO.setup(aerotermia, GPIO.OUT)

mariadb_connection = mariadb.connect(user='pi', database='solar')
cursor = mariadb_connection.cursor()

ahora=datetime.now()
printf("############### INICIO ############## ","")

Iteracion1=True
antes=datetime.now()
diaYaActiva=True

desactivacionConsumo=0

arduino=serial.Serial('/dev/ttyUSB0',baudrate=9600, timeout = 3.0)
txt=''
aerotermiaON=False
newDatosInv=False

consumoDiaAerotermia=0

while True:

        if Iteracion1:
                printf("Estado Aerotermia: ", "Activa por reinicio")
                Iteracion1=False

        ahora=datetime.now()
        printf("FhisteresisExecedentes: ",FhisteresisExecedentes)
        if ahora>FhisteresisExecedentes:
                desactivacionConsumo=0

        ObtenerDatosBD()

        txt=''
        while arduino.inWaiting() > 0:
                txt += arduino.read(1)

        txt2 = txt[txt.find("Potencia = ")+10:txt.find("Irms = ")]
        txt2 = txt2.replace(' ','')
        if not txt2:
                potencia=0
        else:
                potencia=float(txt2)

        printf("Consumo Aerotermia (W): ",potencia)
        consumoDiaAerotermia=consumoDiaAerotermia+(potencia*segundosBucle)/3600
        printf("Consumo Dia Aerotermia (Wh): ",round(consumoDiaAerotermia,2))
        if not aerotermiaON and potencia>2000:
                aerotermiaON=True
                horaAerotermiaON=ahora
                printf("horaAerotermiaON: ",horaAerotermiaON)
        elif aerotermiaON and potencia<500:
                aerotermiaON=False
                horaAerotermiaOFF=ahora
                printf("horaAerotermiaOFF: ",horaAerotermiaOFF)

        newDatosInv=False
        if ObtenerDatosInversor():
                newDatosInv=True
                printf("Potencia desde/hacia la red: ",grid)
                printf("Potencia consumida casa: ",round(load,2))
                printf("Potencia generada PV: ",pv)
        else:
                printf("No ha podido tomar datos del inversor: ","")

        print("HoraFinForzada: ",HoraFinForzada)
        if ahora.day-antes.day:
                consumoDiaAerotermia=0
                diaYaActiva=False
                excedentesAerotermia=excedentesAerotermi1

        printf("Min PV reactivacion: ",desactivacionConsumo)

        if ahora < Fvuelta or not activoBD:
                printf("Estado Aerotermia: ", "Desactivada por BD")
                printf("Fecha de vuelta: ",Fvuelta)
                Activada=False
                desactivacionConsumo=0
                Texterior=True
        elif activoBD==2:
                printf("Estado Aerotermia: ", "Forzada activa por BD")
                ObtenerDatosInversor()
                Activada=True
                desactivacionConsumo=0
        else:
            if newDatosInv:
                consumo=ConsumoRedMedio(potencia)
                ahoraHora=time(ahora.hour,ahora.minute,ahora.second)
                if ahoraHora>HoraInicioForzada and ahoraHora<HoraFinForzada:
                        if not Activada:
                                printf("Estado Aerotermia: ","Activo por hora forzada")
                                Activada=True
                                del consumoRed[:]
                                FhisteresisExecedentes=ahora + timedelta(minutes=minutosHisteresis)
                        if Texterior and sondaBD and grid<excedentesSonda:
                                printf("Temperatura: ","Fija por excedentes")
                                Texterior=False
                        elif grid>0 or not sondaBD:
                                printf("Temperatura: ","Exterior por no tener excedentes o BD")
                                Texteriora=True

                elif not Activada and pv>desactivacionConsumo and (desactivacionConsumo>0 or consumo<consumoMedioArranque)  and ahoraHora>HoraInicioPV and ahoraHora<HoraFinPV and grid<excedentesAerotermi1:
                        printf("Estado Aerotermia: ","Activa por tener suficientes excendentes")
                        Activada=True
                        desactivacionConsumo=0
                        FhisteresisExecedentes=ahora + timedelta(minutes=minutosHisteresis)
                        del consumoRed[:]

                elif Activada:
                       if consumo>consumoDesactivoAerotermia:
                                printf("Estado Aerotermia: ","Desactivada consumo de red " + str(consumo)+"%")
                                desactivacionConsumo=load
                                FhisteresisExecedentes=ahora + timedelta(minutes=minutosHisteresis)
                                Activada=False
                                Texterior=True
                                del consumoRed[:]
                       if Texterior and sondaBD and grid<excedentesSonda:
                                printf("Temperatura: ","Fija por excedentes")
                                Texterior=False
                       elif grid>0 or not sondaBD:
                                printf("Temperatura: ","Exterior por no tener excedentes o BD")
                                Texterior=True

        if sondaBD==2:
                printf("Temperatura: ","Fija por BD")
                Texterior=False
        elif sondaBD==0:
                Texterior=True

        if Activada:
                printf("Ordena Aertotermia: ","Activa")
                diaYaActiva=True
                excedentesAerotermia=excedentesAerotermi2
                if Texterior:
                        printf("Ordena Temperatura: ","Real exterior")
                else:
                        printf("Ordena Temperatura: ","Fija")
        else:
                printf("Ordena Aertotermia: ","Desactivada")
                if Texterior:
                        printf("Ordena Temperatura: ","Real exterior")
                else:
                        printf("Ordena Temperatura: ","Fija")


        GPIO.output(sonda,Texterior)
        GPIO.output(aerotermia,Activada)

        antes=ahora
        sleep(segundosBucle)
