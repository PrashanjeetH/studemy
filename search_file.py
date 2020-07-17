import os
print();
found=0
word = str(input('Enter the string to search:\t'))
os.listdir("./")
for i in os.listdir("./"):
  if 'php' in str(i):
    f = open(i,'r')
    filedata = f.read()
    f.close()

    #newdata = filedata.replace("bookings","courses")

    #f = open(i,'w')
    #f.write(newdata)
    #f.close()*/
    if word in filedata:
      found+=1
      print(found, i)
  
input()
